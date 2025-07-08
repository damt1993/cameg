<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductallForm;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin/all")]
final class ProductAllController extends AbstractController
{
    #[Route("/{dci}/{product}/edit", name: "app_admin_product_all_edit", methods: ["GET", "POST"], requirements: ["dci"=>"\d+", "product"=>"\d+"])]
    #[Route('/', name: 'app_admin_product_all')]
    public function index(?Product $product, Request $request, ProductRepository $repository, EntityManagerInterface $manager): Response
    {
        $product ??=new Product();
        $form = $this->createForm(ProductallForm::class, $product);

        $page = $request->query->get("page");

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $product->setIsEnabled(true);
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute("app_admin_product_all", ["page"=>$page]);
        }
        $products = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->findAllProduct()),
            $request->query->get(key: "page", default: 1),
            maxPerPage: 10
        );
        
        return $this->render('admin/product_all/index.html.twig', [
            'form' => $form,
            'products'=>$products,
            'page' => $page,
        ]);
    }

    #[Route("/{ability}/{product}", name: "app_admin_product_all_ability", methods: ["GET"], requirements: ["product"=>"\d+"])]
    public function allAbility($ability, ?Product $product, EntityManagerInterface $manager, Request $request): Response
    {
        $page = $request->query->get("page");
        if($ability=="abled"){
            $product->setIsEnabled(true);
            $manager->flush();
        } else if ($ability=="enabled"){
            $product->setIsEnabled(false);
            $manager->flush();
        }

        return $this->redirectToRoute("app_admin_product_all", ["page"=>$page]);
    }

}
