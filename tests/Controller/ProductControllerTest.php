<?php


namespace App\Tests\Controller;


use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    protected function setUp()
    {
        $this->client=static::createClient();
    }

    public function testRetrieveTheProductsList(): void
    {
        $response=$this->request('GET', '/api/products');
        $json = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));

        $this->assertArrayHasKey('hydra:totalItems', $json);
        $this->assertEquals(13, $json['hydra:totalItems']);

        $this->assertArrayHasKey('hydra:member', $json);
        $this->assertCount(13, $json['hydra:member']);

    }

    protected function request(string $method, string $uri, $content = null, array $headers = []): Response
    {
        $server = ['CONTENT_TYPE' => 'application/ld+json', 'HTTP_ACCEPT' => 'application/ld+json'];
        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'content-type') {
                $server['CONTENT_TYPE'] = $value;

                continue;
            }

            $server['HTTP_'.strtoupper(str_replace('-', '_', $key))] = $value;
        }

        if (is_array($content) && false !== preg_match('#^application/(?:.+\+)?json$#', $server['CONTENT_TYPE'])) {
            $content = json_encode($content);
       }

        $this->client->request($method, $uri,[],[],$server,$content);

        return $this->client->getResponse();
    }



    public function testThrowErrorsWhenDataAreInvalid(): void
    {
        $data = [
            "name"=> 123,
      "description"=> "Consequatur cupiditate aliquam veritatis aut quo molestias.",
      "currentPrice"=> 314,
      "promotion"=> false,
      "selected"=> true,
      "available"=> true,
        ];

        $response = $this->request('POST', '/api/products', $data);
        $json = json_decode($response->getContent(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));
    }


    public function testCreateAProduct(): void
    {
        $data = [
              "name"=> "une maison a jnane mediouna",
              "description"=> "Consequatur cupiditate aliquam veritatis aut quo molestias de maison belle de jnane mediouna.",
              "currentPrice"=> 54000,
              "promotion"=> false,
              "selected"=> false,
              "available"=> false
        ];

        $response = $this->request('POST', '/api/products', $data);
        $json = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));

        $this->assertArrayHasKey('name', $json);
        $this->assertEquals('une maison a jnane mediouna', $json['name']);
    }


    public function testUpdateAProduct(): void
    {
        $data = [
            'name' => 'maison de soufiane marzouk'
        ];

        $response = $this->request('PUT', $this->findOneIriBy(Product::class, ['name' => 'une maison a jnane mediouna']), $data);
        $json = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));

        $this->assertArrayHasKey('name', $json);
        $this->assertEquals('maison de soufiane marzouk', $json['name']);
    }

    /**
     * Deletes a book.
     */
    public function testDeleteAProduct(): void
    {
        $response = $this->request('DELETE', $this->findOneIriBy(Product::class, ['name' => 'maison de soufiane marzouk']));

        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEmpty($response->getContent());
    }

    protected function findOneIriBy(string $resourceClass, array $criteria): string
    {
        $resource = static::$container->get('doctrine')->getRepository($resourceClass)->findOneBy($criteria);

        return static::$container->get('api_platform.iri_converter')->getIriFromitem($resource);
    }


}