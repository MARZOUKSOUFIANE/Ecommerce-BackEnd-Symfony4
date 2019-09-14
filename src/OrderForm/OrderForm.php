<?php


namespace App\OrderForm;


 use App\Entity\Client;


 class OrderForm {
     /**
      * @var Client
      */
     private $client;
     /**
      * @var OrderProduct[]
      */
     private $products;

     /**
      * OrderForm constructor.
      */
     public function __construct()
     {
     }


     /**
      * @return Client
      */
     public function getClient(): Client
     {
         return $this->client;
     }

     /**
      * @param Client $client
      */
     public function setClient(Client $client): void
     {
         $this->client = $client;
     }

     /**
      * @return OrderProduct[]
      */
     public function getProducts(): array
     {
         return $this->products;
     }

     /**
      * @param OrderProduct[] $products
      */
     public function setProducts(array $products): void
     {
         $this->products = $products;
     }



 }


class OrderProduct{
    /**
     * @var integer
     */
private  $id;
    /**
     * @var integer
     */
private  $quantity;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }



}