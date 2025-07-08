<?php

namespace App\Controller\Admin;

use App\Entity\Dci;
use App\Entity\Product;
use App\Form\ProductAllForm;
use App\Form\ProductForm;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/product')]
final class ProductController extends AbstractController
{

    #[Route("/{dci}/{product}/edit", name: "app_admin_product_edit", methods: ["GET", "POST"], requirements: ["dci"=>"\d+", "product"=>"\d+"])]
    #[Route('/bydci/{dci}', name: 'app_admin_product_bydci', methods: ["GET", "POST"], requirements: ["dci"=>"\d+"])]
    public function byDci(?Dci $dci, ?Product $product, ProductRepository $repository, Request $request, EntityManagerInterface $manager): Response
    {
        $product ??= new Product();
        $form = $this->createForm(ProductForm::class, $product);

        $form->handleRequest($request);
        $page = $request->query->get("productPage");

        if($form->isSubmitted() && $form->isValid()){
            if($product->getId() && $dci instanceof Dci){
                $manager->flush();
            }
            $product->setDci($dci);
            $product->setIsEnabled(true);
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute("app_admin_product_bydci", ["dci"=>$dci->getId(), "page"=>$page]);
        }
                
        $products = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->findAllProductByDci($dci->getId())),
            $request->query->get(key: "page", default: 1),
            maxPerPage: 5
        );

        return $this->render('admin/product/index.html.twig', [
            'form' => $form,
            'products'=> $products,
            'dci'=>$dci,
            'page'=>$page,
        ]);
    }

    #[Route("/{ability}/{dci}/{id}", name: "app_admin_product_ability", methods: ["GET"], requirements: ["dci"=>"\d+", "id"=>"\d+"])]
    public function ability(Request $request, $ability, ?Dci $dci, ?Product $product, EntityManagerInterface $manager): Response
    {
        $page = $request->query->get("productPage");
         if($ability=="abled"){
            $product->setIsEnabled(true);
            $manager->flush();
        } else if ($ability=="enabled"){
            $product->setIsEnabled(false);
            $manager->flush();
        }

        return $this->redirectToRoute("app_admin_product_bydci", ["dci"=>$dci->getId(), "productPage"=>$page]);
    }
}
