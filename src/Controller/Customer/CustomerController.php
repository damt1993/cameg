<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customer', name: 'app_customer_customer')]
final class CustomerController extends AbstractController
{
    #[Route('/', name: 'app_customer_customer')]
    public function index(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $orderPending = $orderRepository->FindSomeStatusOrder($user, 'pending');
        $orderValidate = $orderRepository->FindSomeStatusOrder($user, 'validate');
        
        return $this->render('customer/customer/index.html.twig', [
            'customer' => $this->getUser(),
            'orderPending'=> $orderPending,
            'orderValidate'=> $orderValidate,
        ]);
    }
}
