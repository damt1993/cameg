<?php

namespace App\Controller\Admin;

use App\Entity\Dci;
use App\Form\DciForm;
use App\Repository\DciRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/dci')]
final class DCIController extends AbstractController
{
    #[Route("/{id}/edit", name: "app_admin_dci_edit", methods: ["GET", "POST"], requirements: ["id"=>"\d+"])]
    #[Route('/', name: 'app_admin_dci', methods: ["GET", "POST"])]
    public function index(?Dci $dci, Request $request, EntityManagerInterface $manager, DciRepository $repository): Response
    {
        $dci ??= new Dci();
        $form = $this->createForm(DciForm::class, $dci);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($dci);
            $manager->flush();

            return $this->redirectToRoute('app_admin_dci');
        }

        $dciItems = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->findAllDci()),
            $request->query->get(key: 'page', default: 1),
            maxPerPage: 25
        );
        
        return $this->render('admin/dci/index.html.twig', [
            'form' => $form,
            'dci' => $dciItems,
            'currentDci' => $dci,
        ]);
    }

    #[Route("/{action}/{id}", name: "app_admin_dci_ability", methods: ["GET"], requirements: ["id" => "\d+"])]
    public function ability(?Dci $dci ,Request $request, EntityManagerInterface $manager): Response
    {
        $action = $request->get("action");
        if($action=="is_enabled"){
            $dci->setIsEnabled(true);
        } elseif ($action=="is_desabled") {
            $dci->setIsEnabled(false);
        }
        $manager->flush();
        return $this->redirectToRoute("app_admin_dci");
    }
}
