<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/products", name="api_products")
     */
    public function index(Request $request, ProductRepository $repo): Response
    {
        // Récupèrer les produits associés aux couleurs
        $products = $repo->findByColors($request->get('color'));

        return $this->render('api/index.html.twig', [
            'products' => $products,
        ]);
    }
}
