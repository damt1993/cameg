<?php

namespace App\Controller\Admin;

use App\Entity\Collaborator;
use App\Entity\User;
use App\Form\CollaboratorForm;
use App\Repository\CollaboratorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/collaborator')]
final class CollaboratorController extends AbstractController
{
    #[Route('/new', name: 'app_admin_collaborator_new', methods: ["GET", "POST"])]
    #[Route('/{id}/edit', name: 'app_admin_collaborator_edit', methods: ["GET", "POST"], requirements: ["id"=>"\d+"])]
    public function index(?Collaborator $collaborator, Request $request, EntityManagerInterface $manager, CollaboratorRepository $repository): Response
    {
        $usersId = $repository->findAll();
        $user = $this->getUser();
        $collaborator ??= new Collaborator();
        foreach ($usersId as $userId) {
            if ($user === $userId->getUserId() && !$collaborator->getId()){
                return $this->redirectToRoute("app_admin_collaborator_edit", ["id"=>$userId->getId()]);
            }
        }

        $form = $this->createForm(CollaboratorForm::class, $collaborator);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($user instanceof User && !$collaborator->getId()){
                $collaborator->setUserId($user);
                $collaborator->setLastConnectedAt(new \DateTimeImmutable());
            } else {
                return $this->redirectToRoute("app_login");
            }
            $manager->persist($collaborator);
            $manager->flush();
        }
        return $this->render('collaborator/index.html.twig', [
            'form' => $form,
        ]);
    }
}
