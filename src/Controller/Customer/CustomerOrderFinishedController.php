<?php

namespace App\Controller\Customer;

use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CustomerOrderFinishedController extends AbstractController
{
    #[Route('/customer/customer/order/finished', name: 'app_customer_customer_order_finished')]
    public function index(): Response
    {
        return $this->render('customer/customer_order_finished/index.html.twig', [
            'controller_name' => 'CustomerOrderFinishedController',
        ]);
    }
}
