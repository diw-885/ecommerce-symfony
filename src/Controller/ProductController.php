<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product_list")
     */
    public function list()
    {
        // On récupère les produits en BDD
        $repository = $this->getDoctrine()
            ->getRepository(Product::class);

        // Tous les produits (select * from product) dans un tableau
        $products = $repository->findAll();
        // Le produit (objet) avec l'id 1 (select * from product where id = 1)
        // $product = $repository->find(1);

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/create", name="product_create")
     */
    public function create(Request $request, SluggerInterface $slugger): Response
    {
        // $slugger est un service qu'on récupère avec l'interface SluggerInterface
        // dump($slugger);
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le name est MacBook Pro, le slug est macbook-pro
            $slug = $slugger->slug($product->getName())->lower();
            $product->setSlug($slug);
            $product->setCreatedAt(new \DateTimeImmutable());

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{slug}", name="product_show")
     *
     * Symfony va interpréter l'argument Product avec le Param Converter, il va chercher le produit dont le slug
     * correspond dans l'URL.
     */
    public function show(Product $product)
    {
        // Première solution sans la magie du ParamConverter avec le paramètre $slug
        // $repository = $this->getDoctrine()->getRepository(Product::class);
        // $product = $repository->findOneBySlug($slug);
        // dump($product);

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
