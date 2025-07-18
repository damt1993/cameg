<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerDataForm;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customer/customerdata')]
final class CustomerdataController extends AbstractController
{
    #[Route('/new', name: 'app_customer_customerdata')]
    #[Route('/{id}/edit', name: 'app_customer_customerdata_edit', methods: ["GET", "POST"], requirements: ["id"=>"\d+"])]
    public function index(Request $request, EntityManagerInterface $manager, CustomerRepository $repository, ?Customer $customer): Response
    {
        $customer ??= new Customer();
        $usersId = $repository->findAll();
        $user = $this->getUser();
        foreach ($usersId as $userId) {
            if ($user === $userId->getUserId() && !$customer->getId()){
                return $this->redirectToRoute("app_customer_customerdata_edit", ["id"=>$userId->getId()]);
            }
        }

        $form = $this->createForm(CustomerDataForm::class, $customer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($user instanceof User && !$customer->getId()){
                $customer->setUserId($user);
                $customer->setLastConnectedAt(new \DateTimeImmutable());
            } else {
                return $this->redirectToRoute("app_login");
            }
            $manager->persist($customer);
            $manager->flush();
            return $this->redirectToRoute("app_customer_customerdata");
        }
        return $this->render('customer/customerdata/index.html.twig', [
            'form' => $form,
        ]);
    }
}
