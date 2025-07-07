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
    #[Route("/{product}/edit", name: "app_admin_product_all_edit", methods: ["GET", "POST"], requirements: ["product"=>"\d+"])]
    #[Route('/', name: 'app_admin_product_all')]
    public function index(Request $request, ProductRepository $repository, EntityManagerInterface $manager): Response
    {
        $product =new Product();
        $form = $this->createForm(ProductAllForm::class, $product);

        $products = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->findAllProduct()),
            $request->query->get(key: "page", default: 1),
            maxPerPage: 10
        );
        return $this->render('admin/product/index.html.twig', [
            'form' => $form,
            'products'=>$products,
        ]);
    }

    #[Route("/{ability}/{product}", name: "app_admin_product_all_ability", methods: ["GET"], requirements: ["product"=>"\d+"])]
    public function allAbility($ability, ?Dci $dci, ?Product $product, EntityManagerInterface $manager): Response
    {
        if($ability=="abled"){
            $product->setIsEnabled(true);
            $manager->flush();
        } else if ($ability=="enabled"){
            $product->setIsEnabled(false);
            $manager->flush();
        }

        return $this->redirectToRoute("app_admin_product_all");
    }


    #[Route("/{dci}/{product}/edit", name: "app_admin_product_edit", methods: ["GET", "POST"], requirements: ["dci"=>"\d+", "product"=>"\d+"])]
    #[Route('/bydci/{dci}', name: 'app_admin_product_bydci', methods: ["GET", "POST"], requirements: ["dci"=>"\d+"])]
    public function byDci(?Dci $dci, ?Product $product, ProductRepository $repository, Request $request, EntityManagerInterface $manager): Response
    {        
        $product ??= new Product();
        $form = $this->createForm(ProductForm::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($product->getId() && $dci instanceof Dci){
                $manager->flush();
            }
            $product->setDci($dci);
            $product->setIsEnabled(true);
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute("app_admin_product_bydci", ["dci"=>$dci->getId()]);
        }
        $productQuery = $repository->findAllProductByDci($dci->getId());
        $products = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($productQuery),
            $request->query->get(key: "page", default: 1),
            maxPerPage: 10
        );

        return $this->render('admin/product/bydci.html.twig', [
            'form' => $form,
            'products'=> $products,
            'dci'=>$dci,
        ]);
    }

    #[Route("/{ability}/{dci}/{id}", name: "app_admin_product_ability", methods: ["GET"], requirements: ["dci"=>"\d+", "id"=>"\d+"])]
    public function ability($ability, ?Dci $dci, ?Product $product, EntityManagerInterface $manager): Response
    {
        if($ability=="abled"){
            $product->setIsEnabled(true);
            $manager->flush();
        } else if ($ability=="enabled"){
            $product->setIsEnabled(false);
            $manager->flush();
        }

        return $this->redirectToRoute("app_admin_product_bydci", ["dci"=>$dci->getId()]);
    }
}
