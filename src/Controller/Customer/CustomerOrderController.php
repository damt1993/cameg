<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Entity\Order;
use App\Enum\OrderStatus;
use App\Form\OrderForm;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customer/customerorder')]
final class CustomerOrderController extends AbstractController
{
    #[Route('', name: 'app_customer_customerorder_new')]
    public function index(ProductRepository $repository, OrderRepository $orderRepository): Response
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

        $orderId = $orderRepository->GetLastOrderIdOfCurrentCustomer($user);
        if (count($orderId)!= 0){
            $orderId = $orderId[0]->getOrderNumber();
        } else {
            $orderId = 0;
        }

        $data = [
            "client"=>$user->getUsername(),
            "date"=> new \DateTimeImmutable("now", new DateTimeZone("GMT")),
            "commande"=>$orderId+1,
            "data"=>[],
        ];
        $jsonData = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        $phpOrderItem = [];
        //If the path is not exist create it
        if (!is_file($file)){
            file_put_contents($file, $jsonData);
        // If it exist
        } else {
            //Get the file content
            $orderItem = file_get_contents($file);
            //Convert content in php object
            $phpOrderItem = json_decode($orderItem, true)['data'];
        }

        return $this->render('customer/customerorder/new.html.twig', [
            'form' => $form,
            'product'=> $productList,
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
        if (isset($data)){
            $item = $_POST;
            $id = htmlspecialchars($item['id']);
            $quantity = htmlspecialchars($item['quantity']);

            /** @var User */
            $user = $this->getUser();

            //file directory
            $file = 'order/'.$user->getId().'.json';
            
            //Verify if directory exist
            if (is_file($file)){
                //Get file content
                $data = file_get_contents($file);
            }

            //Convert data in json to php object
            $jsonData = json_decode($data, true);

            //Return value
            $returnValue = [];

            if ($id>0 && $quantity>0){
                $productFind = 0;
                for ($i=0; $i < count($jsonData["data"]); $i++) { 
                    if ($jsonData["data"][$i]["id"]== $id){
                        $jsonData["data"][$i]["quantity"] += $quantity;
                        $returnValue = ["id"=>$id,"newQuantity"=>$jsonData["data"][$i]["quantity"]];
                        $productFind += 1;
                        break;
                    }
                }
                if ($productFind<=0){
                    $itemOrder = [
                        'id'=>intval(htmlspecialchars($item['id'])),
                        'name'=>htmlspecialchars($item['name']),
                        'price'=>intval(htmlspecialchars($item['price'])),
                        'publicPrice'=>intval(htmlspecialchars($item['publicPrice'])),
                        'peromptAt'=>htmlspecialchars($item['peromptAt']),
                        'quantity'=>intval(htmlspecialchars($item['quantity'])),
                    ];
                    //Add the new item in the first position in data array
                    array_unshift($jsonData['data'], $itemOrder);

                    //Concive the item html for order basket
                    $returnValue = "
                        <div id='item".$id."' class='row mb-3'>
                            <div class='col-4 close-button'>
                                <button class='btn btn-outline-danger'><i class='bi bi-trash3'></i></button>
                                ".$itemOrder['name']."
                            </div>
                            <div class='col-2 newQuantityBox'>
                                <div class='quantity'>
                                    <div class='input-group'>
                                        <span class='input-group-btn input-group-prepend'>
                                            <button class='btn btn-dark remove' type='button'>-</button>
                                        </span>
                                        <input type='number' name='quantity' id='input".$itemOrder['id']."' class='form-control' value='".$itemOrder['quantity']."' min='0' width='100'>
                                        <span class='input-group-btn intup-group-append'>
                                            <button class='btn btn-dark add' type='button'>+</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-2 orderPrice'>".$itemOrder['price']."</div>
                            <div class='col-2 orderPublicPrice'>".$itemOrder['publicPrice']."</div>
                            <div class='col-2 orderValue'>".$itemOrder['quantity']*$itemOrder['price']."</div>
                        </div>
                    ";
                }
            }
        }
        //Ordor the content to be readible
        $productItemOrder = json_encode($jsonData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        //Put the new content in the order file
        file_put_contents($file, $productItemOrder);
        return new JsonResponse(["data"=>$returnValue]);

    }

    #[Route('/quantityupdate', name: 'app_customer_customer_quantityupdate', methods: ["GET", "POST"])]
    public function quantityUpdate(Request $request): JsonResponse
    {
        //Vérifier si c'est une requete ajax
        if(!$request->isXmlHttpRequest()){
            return new JsonResponse(['error'=> 'Cette requete n\'est pas une requète AJAX', 'status'=>'error']);
        }

        //Récupérer les données via JSON
        $data = $request->getContent();
        
        if (!$data){
            return new JsonResponse(["data"=>"Aucune donné n'a été transmise"]);
        } else {
            $item = $_POST;
            $id = htmlspecialchars($item["id"]);
            $quantity = intval(htmlspecialchars($item["quantity"]));

            if ($id>0 && $quantity>0){
                /** @var User */
                $user = $this->getUser();

                //file directory
                $file = 'order/'.$user->getId().'.json';
                
                //Verify if directory exist
                if (is_file($file)){
                    //Get file content
                    $data = file_get_contents($file);
                }

                $montant = 0;
                //Convert data in json to php object
                $jsonData = json_decode($data, true);
                for ($i=0; $i < count($jsonData["data"]); $i++) { 
                    if ($jsonData["data"][$i]["id"]== $id){
                        $jsonData["data"][$i]["quantity"] = $quantity;
                        $montant = $quantity*$jsonData["data"][$i]["price"];
                        break;
                    }
                }
                //Ordor the content to be readible
                $productItemOrder = json_encode($jsonData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                //Put the new content in the order file
                file_put_contents($file, $productItemOrder);
            }
        }

        return new JsonResponse(["data"=>"Quantité changé", "montant"=>$montant, "id"=>$id]);
    }

    #[Route('/validate', name: 'app_customer_customer_validate', methods: ["GET", "POST"])]
    public function validate(EntityManagerInterface $manager, Order $order, ?Customer $customer)
    {
        /** @var User */
        $user = $this->getUser();

        //file directory
        $file = 'order/'.$user->getId().'.json';
        
        //Verify if directory exist
        if (is_file($file)){
            //Get file content
            $data = file_get_contents($file);
        }
        //Convert data in json to php object
        $jsonData = json_decode($data, true);

        if (count($jsonData["data"])>0){
            $order->setCustomer($this->getUser());
            $order->setProductList($jsonData["data"]);
            $order->setOrderedAt(new \DateTimeImmutable("now", new DateTimeZone("GMT")));
            $order->setOrderNumber($jsonData["commande"]);
            $order->setStatus(OrderStatus::Validate);

            $manager->persist($order);
            $manager->flush();

            //delete the json file who contain the order
            unlink($file);
        }


        return $this->redirectToRoute("app_customer_customerorder_new");

    }

    #[Route('/pending', name: 'app_customer_customer_pending', methods: ["GET", "POST"])]
    public function Pending(EntityManagerInterface $manager, Order $order, ?Customer $customer)
    {
        /** @var User */
        $user = $this->getUser();

        //file directory
        $file = 'order/'.$user->getId().'.json';
        
        //Verify if directory exist
        if (is_file($file)){
            //Get file content
            $data = file_get_contents($file);
        }
        //Convert data in json to php object
        $jsonData = json_decode($data, true);

        if (count($jsonData["data"])>0){
            $order->setCustomer($this->getUser());
            $order->setProductList($jsonData["data"]);
            $order->setOrderedAt(new \DateTimeImmutable("now", new DateTimeZone("GMT")));
            $order->setOrderNumber($jsonData["commande"]);
            $order->setStatus(OrderStatus::Pending);

            $manager->persist($order);
            $manager->flush();

            //delete the json file who contain the order
            unlink($file);
        }


        return $this->redirectToRoute("app_customer_customerorder_new");

    }

    #[Route('/delete', name: 'app_customer_customer_delete', methods: ["GET", "POST"])]
    public function DeleteOrder(EntityManagerInterface $manager, Order $order, ?Customer $customer)
    {
        /** @var User */
        $user = $this->getUser();

        //file directory
        $file = 'order/'.$user->getId().'.json';
        
        //Verify if directory exist
        if (is_file($file)){
            //Get file content
            $data = file_get_contents($file);
        }
        //Convert data in json to php object
        $jsonData = json_decode($data, true);

        if (count($jsonData["data"])>0){
            $order->setCustomer($this->getUser());
            $order->setProductList($jsonData["data"]);
            $order->setOrderedAt(new \DateTimeImmutable("now", new DateTimeZone("GMT")));
            $order->setOrderNumber($jsonData["commande"]);
            $order->setStatus(OrderStatus::Delete);

            $manager->persist($order);
            $manager->flush();

            //delete the json file who contain the order
            unlink($file);
            
        }


        return $this->redirectToRoute("app_customer_customerorder_new");

    }

    #[Route('/deleteproduct', name: 'app_customer_customer_deleteproduct', methods: ["GET", "POST"])]
    public function deleteProduct(Request $request): JsonResponse
    {
        //Vérifier si c'est une requete ajax
        if(!$request->isXmlHttpRequest()){
            return new JsonResponse(['error'=> 'Cette requete n\'est pas une requète AJAX', 'status'=>'error']);
        }

        //Récupérer les données via JSON
        $data = $request->getContent();
        
        //Vérifier si les données existent
        if (isset($data)){
            $item = $_POST;
            $id = htmlspecialchars($item['id']);

            /** @var User */
            $user = $this->getUser();

            //file directory
            $file = 'order/'.$user->getId().'.json';
            
            //Verify if directory exist
            if (is_file($file)){
                //Get file content
                $data = file_get_contents($file);
            }

            //Convert data in json to php object
            $jsonData = json_decode($data, true);

            if ($id>0){
                for ($i=0; $i < count($jsonData["data"]); $i++) { 
                    if ($jsonData["data"][$i]["id"]== $id){
                        array_splice($jsonData["data"], $i, 1);
                        break;
                    }
                }
            }
            //Ordor the content to be readible
            $productItemOrder = json_encode($jsonData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
            //Put the new content in the order file
            file_put_contents($file, $productItemOrder);
        }
        return new JsonResponse(["data"=>"Donné supprimé", "id"=>$id]);
    }
}
