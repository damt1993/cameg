<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customer/orders')]
final class OrdersController extends AbstractController
{
    #[Route('/saved', name: 'app_customer_order_saved', methods: ["GET"])]
    public function saved(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $orderValidate = $orderRepository->FindSomeStatusOrder($user, 'validate');

        /** @var User */
        $user = $this->getUser();

        //file directory
        $file = 'order/'.$user->getId().'.json';
        
        //Verify if directory exist
        if (is_file($file)){
            //Get file content
            $newOrderButton = "Continuer la commande en cours";
        } else {
            $newOrderButton = "Créer une nouvelle commande";
        }
        
        return $this->render('customer/customer/saved.html.twig', [
            'customer' => $user->getCustomer(),
            'orderValidate'=> $orderValidate,
            'newOrderButton'=> $newOrderButton,
        ]);
    }

    #[Route('/pending', name: 'app_customer_order_pending')]
    public function pending(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $orderPending = $orderRepository->FindSomeStatusOrder($user, 'pending');

        /** @var User */
        $user = $this->getUser();

        //file directory
        $file = 'order/'.$user->getId().'.json';
        
        //Verify if directory exist
        if (is_file($file)){
            //Get file content
            $newOrderButton = "Continuer la commande en cours";
        } else {
            $newOrderButton = "Créer une nouvelle commande";
        }
        
        return $this->render('customer/customer/pending.html.twig', [
            'customer' => $user->getCustomer(),
            'orderPending'=> $orderPending,
            'newOrderButton'=> $newOrderButton,
        ]);
    }
}
