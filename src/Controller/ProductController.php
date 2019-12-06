<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }


    /**
     * @Route("/product/store", name="product", methods={"POST"})
     */
    public function store(ValidatorInterface $validator, Request $request)
    {
        //Getting the json data and decode it
        $content = $request->getContent();
        $data = json_decode($content);

        //Get the manager and the address object
        $entityManager = $this->getDoctrine()->getManager();

        $errors = $validator->validate($data);
        if($errors > 0){
            $errorMessages = [];
            foreach ($errors as $formError) {
                $errorMessages[] = $formError->getMessage();
            }
            $response = new JsonResponse($errorMessages);
            return $response;
        }


        /** @var Product $product */
        $product = new Product();
        $product->setName($data->products->name);
        $product->setPrice($data->products->price);



        $entityManager->persist($product);
        $entityManager->flush();

        $response = new JsonResponse(['data' => 'A new product is made with '. $product->getId()]);

        return $response;


    }
}
