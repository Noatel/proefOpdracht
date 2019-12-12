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
use JMS\Serializer\SerializationContext;


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
     * @Route("/order/getOrders", name="getOrderrs")
     */
    public function getOrders()
    {
        //Getting all the orders from the Entity Order
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findAll();

        //Serialize the array to an json object
        $serializer = JMS\Serializer\SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($orders, 'json', SerializationContext::create()->setGroups(array('order')));

        // Return a Response with encoded Json
        return new Response($jsonContent, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/order/getOrderDetails", name="getOrdersDetails")
     */
    public function getOrderDetails()
    {
        //Getting all the orders from the Entity Order
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findAll();

        //Serialize the array to an json object
        $serializer = JMS\Serializer\SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($orders, 'json', SerializationContext::create()->setGroups(array('address', 'order', 'product')));

        // Return a Response with encoded Json
        return new Response($jsonContent, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/order/store", name="store", methods={"POST"})
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return JsonResponse
     */
    public function store(ValidatorInterface $validator, Request $request)
    {
        //Getting the json data and decode it
        $content = $request->getContent();
        $data = json_decode($content);



        //Get the manager and the address object
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Address $address */
        $repository = $this->getDoctrine()->getRepository(Address::class);
        $address = $repository->find($data->orders->address);

        //Create an Order
        $order = New Order();
        $order->setAddress($address);
        $order->setEmail($data->orders->email);

        $repository = $this->getDoctrine()->getRepository(Order::class);
        /** @var Order $order */
        $latestProduct = $repository->findOneBy([], ['id' => 'desc']);

        //Create an Order Rule
        //Check if the the product oot a latest product
        $productName = "";
        if (isset($latestProduct)) {
            $latestProductId = $latestProduct->getId() + 1;
        } else {
            $latestProductId = 1;
        }

        //If there are errors
        $errors = $validator->validate($order);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $formError) {
                $errorMessages[] = $formError->getMessage();
            }
            $response = new JsonResponse($errorMessages);
            return $response;
        }

        //If the orders got more then 1 product
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

                //Get the product name + id, we are going to use this for a reference
                $productName = substr($product->getName(), 0, 3) . "-" . $latestProductId;
            }
        } else {
            //If the order doesnt have any products
            $response = new JsonResponse(['error' => 'Please enter a product']);
            return $response;
        }

        //Set the reference
        $order->setReference($productName);

        //Set the order and flush it to the database
        $entityManager->persist($order);
        $entityManager->flush();

        $response = new JsonResponse(['data' => 'Saved new order with id ' . $order->getId()]);

        return $response;

    }

    /**
     * @Route("/order/update/{id}", name="update", methods={"POST"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        /** @var Order $order */
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        //Check if the order is exsist
        if (!$order) {
            $response = new JsonResponse(['error' => 'Please enter a valid order id']);
            return $response;
        }

        //Get the json data & decode it
        $jsonData = $request->getContent();
        $newOrder = json_decode($jsonData);

        foreach ($newOrder as $data) {
            //Check if the email is not empty
            if (!empty($data->email)) {
                $order->setEmail($data->email);
            }

            //Check if the address_id is not empty
            if (!empty($data->address_id)) {
                /** @var Address $address */
                $address = $this->getDoctrine()->getRepository(Address::class)->find($data->address_id);
                $order->setAddress($address);
            }

            //Check if the products is not empty
            if (!empty($data->products)) {
                //Remove the many to many relations
                foreach ($order->getOrderRule() as $orderRule) {
                    $order->removeOrder($orderRule);
                    $entityManager->persist($order);
                }
                //Foreach products met ids
                foreach ($data->products as $productId) {

                    //Check if the product id isnt null
                    if (!empty($productId)) {

                        /** @var Product $product */
                        $product = $this->getDoctrine()->getRepository(Product::class)->find($productId);
                        //Check if the product is valid
                        if (!empty($product)) {
                            /** @var OrderRule $orderRule */
                            $orderRule = New OrderRule();
                            $orderRule->setOrder($order);
                            $orderRule->setProduct($product);
                            $orderRule->setQuantity(count($data->products));
                            $entityManager->persist($orderRule);
                        }
                    }
                }
            }
        }

        //Set the updated order and flush it into the database
        $entityManager->persist($order);
        $entityManager->flush();

        $response = new JsonResponse(['data' => 'Updated the order with id ' . $order->getId()]);
        return $response;
    }

    /**
     * @Route("/order/delete/{id}", name="delete", methods={"POST"})
     */
    public function delete($id)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($order);
        $entityManager->flush();

        $response = new JsonResponse(['data' => 'The order with id ' . $id . ' is deleted']);
        return $response;
    }


}
