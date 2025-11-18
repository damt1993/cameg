<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class userConnectedService implements AuthenticationSuccessHandlerInterface
{

  private $router;

  public function __construct(RouterInterface $router)
  {
    $this->router =$router;
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
  {
    /** @var User */
    $user = $token->getUser();
  
    if (in_array("ROLE_CLIENT", $user->getRoles(), true)){
      if ($user->getCustomer()== null) {
        $redirection = new RedirectResponse($this->router->generate("app_customer_customerdata"));
      } else {
        $redirection = new RedirectResponse($this->router->generate("app_customer_customerdata_show"));
      }
    } else {
      if ($user->getCollaborator()== null){
        $redirection = new RedirectResponse($this->router->generate("app_admin_collaborator_new"));
      } else {
        $redirection = new RedirectResponse($this->router->generate("app_admin_collaborator_show"));    
      }
    }
    return $redirection;
  }
}