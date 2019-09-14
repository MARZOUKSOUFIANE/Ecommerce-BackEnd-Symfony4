<?php


namespace App\Controller;
use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Ordere;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\OrderForm\OrderForm;
use App\Repository\ClientRepository;
use App\Repository\ProductsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Provider\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class ProductController extends AbstractController
{

    /**
     * @var OrderForm
     */
    private $orderForm;
    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var ProductsRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProductsRepository $repository,ClientRepository $clientRepository ,ObjectManager $em, OrderForm $orderForm)
    {
        $this->repository=$repository;
        $this->em=$em;
        $this->orderForm = $orderForm;
        $this->clientRepository = $clientRepository;
    }

    /**
     * @Route(path="/api/photoProduct/{id}",name="show.product.image")
     * @param Product $product
     * @return BinaryFileResponse
     */
    public function getProductImage($id){
        $product=$this->repository->find($id);
        $publicResourcesFolderPath = $this->getParameter('kernel.project_dir'). '/public/images/';
        return new BinaryFileResponse($publicResourcesFolderPath.$product->getPhotoName());
    }

    /**
     * @Route(path="/api/uploadPhoto/{id}",name="upload.photo")
     * @param $id
     * @param Request $request
     */
    public function uploadPhoto($id,Request $request){
        $file=$request->files->get('file');
        $destination = $this->getParameter('kernel.project_dir'). '/public/images/';
        move_uploaded_file($file,$destination.$id.".png");

        $product=$this->repository->find($id);
        $product->setPhotoName($id.".png");
        $this->em->flush();

        $response=new Response();
        $response->setStatusCode(Response::HTTP_OK);
        return $response;

    }

    /**
     * @Route(path="/api/orders",name="order.save",methods={"POST"})
     * @param Request $request
     */
    public function saveOrder(Request $request){
        $data=json_decode($request->getContent());

        $client=new Client();
        $client->setName($data->client->name);
        $client->setAddress($data->client->address);
        $client->setEmail($data->client->email);
        $client->setPhoneNumber($data->client->phoneNumber);
        $client->setUsername($data->client->username);
        $this->em->persist($client);
        $this->em->flush();

        $products=$data->products;
        $order=new Ordere();
         $order->setClient($client);
         $order->setDate(new \DateTime());
         $order->setTotalAmount($data->totalAmount);
         $this->em->persist($order);
         $this->em->flush();


        for($i = 0; $i < count($data->products); $i++){
            $product=$this->repository->find($products[$i]->id);
            $pi=new OrderItem();
            $pi->setPrice($product->getCurrentPrice());
            $pi->setProduct($product);
            $pi->setQuantity($products[$i]->quantity);
            $this->em->persist($pi);
            $this->em->flush();
            $order->addOrderItem($pi);
        }

        $this->em->flush();
        //$client->addOrder($order);

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($order, 'json',['ignored_attributes' => ['client','orderItems','payment']]);

        return new Response($jsonContent);

    }

}