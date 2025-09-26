<?php

namespace App\Controller\Customer;

use App\Entity\Order;
use App\Form\OrderForm;
use App\Repository\ProductRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customer/customerorder')]
final class CustomerOrderController extends AbstractController
{
    #[Route('', name: 'app_customer_customerorder_new')]
    public function index(ProductRepository $repository): Response
    {
        $product = $repository->findAllProduct()->getQuery()->getResult();
        $productList = [];

        foreach ($product as $uniqueProduct) {
            $productList[] = [
                'id'=>$uniqueProduct->getId(),
                'name'=>$uniqueProduct->getName(),
                'price'=>$uniqueProduct->getPrice(),
                'publicPrice'=>$uniqueProduct->getPublicPrice(),
                'peromptAt'=>date_format($uniqueProduct->getPeromptAt(), 'd m Y'),
            ];
        }
        $order = new Order();
        $form = $this->createForm(OrderForm::class, $order);
        
        //Vérifier l'existance du fichier s'il n'existe pas, le créer
        /** @var User */
        $user = $this->getUser();  
        $file = 'order/'.$user->getId().'.json';

        $data = [
            "client"=>$user->getUsername(),
            "date"=> new \DateTimeImmutable(),
            "commande"=>$user->getUsername().$user->getId(),
            "data"=>[],
        ];
        $jsonData = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        $phpOrderItem = [];
        if (!is_file($file)){
            file_put_contents($file, $jsonData);
        } else {
            $orderItem = file_get_contents($file);
            $phpOrderItem = json_decode($orderItem, true)['data'];
        }
        $orderItemer = file_get_contents('../templates/orderItem.html.twig');

        return $this->render('customer/customerorder/new.html.twig', [
            'form' => $form,
            'product'=> $productList,
            'orderItemer'=> json_encode($orderItemer),
            'phpOrderItem' => $phpOrderItem,
        ]);
    }

    #[Route('/update', name: 'app_customer_customerorder_update', methods: ["GET", "POST"])]
    public function update(Request $request): JsonResponse
    {
        //Vérifier si c'est une requete ajax
        if(!$request->isXmlHttpRequest()){
            return new JsonResponse(['error'=> 'Cette requete n\'est pas une requète AJAX', 'status'=>'error']);
        }

        //Récupérer les données via JSON
        $data = $request->getContent();
        
        //Vérifier si les données existent
        if(isset($data)){
            $item = $_POST;
            $id = htmlspecialchars($item['id']);
            $quantity = htmlspecialchars($item['quantity']);

            /** @var User */
            $user = $this->getUser();

            $file = 'order/'.$user->getId().'.json';
            if (is_file($file)){
                $data = file_get_contents($file);
            }

            $jsonData = json_decode($data, true);

            if ($id>0 && $quantity>0){
                //On recupère les données de la commande du produit dans un objet pour l'ajouter à la commande en cours
                $itemOrder = [
                    'id'=>intval(htmlspecialchars($item['id'])),
                    'name'=>htmlspecialchars($item['name']),
                    'price'=>intval(htmlspecialchars($item['price'])),
                    'publicPrice'=>intval(htmlspecialchars($item['publicPrice'])),
                    'peromptAt'=>htmlspecialchars($item['peromptAt']),
                    'quantity'=>intval(htmlspecialchars($item['quantity'])),
                ];
                array_unshift($jsonData['data'], $itemOrder);
                $productItemOrder = json_encode($jsonData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                file_put_contents($file, $productItemOrder);

                $itemAdd = "
                    <div id='item".$id."' class='row mb-3'>
                        <div class='col-4'>
                            <button class='btn btn-outline-danger'>X</button>
                            ".htmlspecialchars($item['name'])."
                        </div>
                        <div class='col-2 orderQuantityAjax'>
                            <div class='newQuantity'>
                                <div class='input-group'>
                                    <span class='input-group-btn input-group-prepend'>
                                        <button class='btn btn-dark remove' type='button'>-</button>
                                    </span>
                                    <input type='number' name='quantity' id='' class='form-control' value='".htmlspecialchars($item['quantity'])."' min='0' width='100'>
                                    <span class='input-group-btn intup-group-append'>
                                        <button class='btn btn-dark add' type='button'>+</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class='col-2 orderPrice'>".htmlspecialchars($item['price'])."</div>
                        <div class='col-2 orderPublicPrice'>".htmlspecialchars($item['publicPrice'])."</div>
                        <div class='col-2 orderValue'>Montant</div>
                    </div>
                ";

                return new JsonResponse(['message'=>"Parfait, on peut enregistrer ".$item['name'], 'status'=>'Succes', 'blocCode'=>$itemAdd]);
            } else {
                return new JsonResponse(['error'=>"Le produit ".$item['name']." ne peut pas être enregistré", 'status'=>'error']);
            }
        } else {
            return new JsonResponse(['message'=>'Aucune donnée transmise', 'status'=>'error']);
        }
        
        //Vérifier les données
/*        if (isset($data['id'])){
            $id = $data['id'];
            $resultat = "Traitement éffectué avec la valeur : " . htmlspecialchars($id);
            return $this->json(['message' => $resultat, 'status' => 'success']);
        } else {
            //renvoi d'une erreur
            return $this->json(['message'=>'Données manquantes', 'status'=>'error', JsonResponse::HTTP_BAD_REQUEST]);
        }*/
    }
}
