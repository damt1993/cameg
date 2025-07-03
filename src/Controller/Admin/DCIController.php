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
    #[Route('/', name: 'app_admin_dci', methods: ["GET", "POST"])]
    public function index(Request $request, EntityManagerInterface $manager, DciRepository $repository): Response
    {
        $dci = new Dci();
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
        ]);
    }
}
