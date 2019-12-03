<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderRule;
use App\Entity\Product;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Serializer;
use JMS;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function index()
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    /**
     * @Route("/order/create", name="create")
     */
    public function create()
    {
        $repository = $this->getDoctrine()->getRepository(Address::class);
        $adresses = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();

        return $this->render('order/create.html.twig', [
            'controller_name' => 'OrderController',
            'adresses' => $adresses,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/order/getOrders", name="create")
     */
    public function getOrders()
    {

        //Getting all the orders from the Entity Order
        $orders = $this->getDoctrine()
                  ->getRepository(Order::class)
                  ->findAll();

        //Creating an array
        $arrayCollection = array();
        $i = 0;
        $j = 0;

        /** @var Order $order */
        foreach ($orders as $order) {
            //Defining the Order_reference and email in the array
            $arrayCollection[$i] = array(
                'order_reference' => $order->getReference(),
                'email' => $order->getEmail(),
            );


            /** @var Address $address */
            $addres = $order->getAddress();

            //Getting the address and putting the values in the array address
            $arrayCollection[$i]["address"]["Address"] = $addres->getAddress();
            $arrayCollection[$i]["address"]["House_number"] = $addres->getHouseNumber();
            $arrayCollection[$i]["address"]["Postal_Code"] = $addres->getPostalCode();
            $arrayCollection[$i]["address"]["Residence"] = $addres->getResidence();
            $arrayCollection[$i]["address"]["Country"] = $addres->getCountry();

            /** @var OrderRule $order_rule */
            $order_rules = $order->getOrderRule();

            //Getting all the order rules, get the products from them
            foreach ($order_rules as $order_rule) {

                /** @var Product $product */
                $product = $order_rule->getProduct();

                //Because we only want the name from the product
                $arrayCollection[$i]["products"][$j] = $product->getName();
                $j++;
            }

            //Reset the value and adding the $i up to increase the array size
            $j = 0;
            $i++;
        }

        //Serilize the array to an json object
        $serializer = JMS\Serializer\SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($arrayCollection, 'json');

        // Return a Response with encoded Json
        return new Response($jsonContent, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/order/createOrder", name="createOrder", methods={"POST"})
     */
    public function createOrder(ValidatorInterface $validator, Request $request)
    {
        $content = $request->getContent();
        $data = json_decode($content);

        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Address::class);

        /** @var Address $adres */
        $adres = $repository->find($data->orders->adres_id);

        //Create an Order
        $order = New Order();
        $order->setAddress($adres);
        $order->setEmail($data->orders->email);

        $repository = $this->getDoctrine()->getRepository(Order::class);
        /** @var Order $order */
        $latestProduct = $repository->findOneBy([], ['id' => 'desc']);

        //Create an Order Rule
        //Check if the the product oot a latest product
        $productName = "";
        if (isset($latestProduct)) {
            $id = $latestProduct->getId() + 1;
        } else {
            $id = 1;
        }

        $errors = $validator->validate($order);

        if (count($errors) > 0) {
            return $this->render('errors/validation.html.twig', [
                'errors' => $errors,
            ]);
        }

        if ($data->orders->products > 0) {
            foreach ($data->orders->products as $product) {

                $repository = $this->getDoctrine()->getRepository(Product::class);

                /** @var Product $product */
                $product = $repository->find($product);
                $quantity = count($data->orders->products);

                //Create an order rule
                $orderRule = New OrderRule();
                $orderRule->setOrder($order);
                $orderRule->setProduct($product);
                $orderRule->setQuantity($quantity);
                $entityManager->persist($orderRule);

                $productName = substr($product->getName(), 0, 3) . "-" . $id;
            }
        } else {
            $response = new JsonResponse(['error' => 'Please enter a product']);
            return $response;
        }

        $order->setReference($productName);


        $entityManager->persist($order);
        $entityManager->flush();

        $response = new JsonResponse(['data' => 'Saved new order with id ' . $order->getId()]);

        return $response;

    }

}
