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
        
        return $this->render('customer/customer/index.html.twig', [
            'customer' => $user->getCustomer(),
            'orderPending'=> $orderPending,
            'orderValidate'=> $orderValidate,
            'newOrderButton'=> $newOrderButton,
        ]);
    }
}
