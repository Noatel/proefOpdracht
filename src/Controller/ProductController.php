<?php

namespace App\Controller;

use App\Entity\Product;
use App\Validator\Form;
use App\Validator\FormValidator;
use App\Validator\ProductValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\ProductType;

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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        //Getting the json data and decode it
        $data = json_decode($request->getContent(), true);

        /** @var Product $product */
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setName($data["name"]);
            $product->setPrice($data["price"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $response = new JsonResponse(['data' => 'A new product is made with ' . $product->getId()]);
            return $response;
        } else {
            $response = new JsonResponse(['data' => (string)$form->getErrors(true)]);
            return $response;
        }
    }

    /**
     * @Route("/product/update/{id}", name="updateProduct", methods={"POST"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        //Check if the product is exsist
        if (!$product) {
            $response = new JsonResponse(['error' => 'Please enter a valid product id']);
            return $response;
        }

        //Getting the json data and decode it
        $data = json_decode($request->getContent(), true);

        /** @var Product $product */
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setName($data["name"]);
            $product->setPrice($data["price"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $response = new JsonResponse(['data' => 'Product Updated ' . $product->getId()]);
            return $response;
        } else {
            $response = new JsonResponse(['data' => (string)$form->getErrors(true)]);
            return $response;
        }
    }
}
