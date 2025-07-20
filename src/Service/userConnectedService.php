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
    $user = $token->getUser();

    if (in_array("ROLE_CLIENT", $user->getRoles(), true)){
      $redirection = new RedirectResponse($this->router->generate("app_customer_customerdata"));
    } else {
      $redirection = new RedirectResponse($this->router->generate("app_admin_collaborator_new"));
    }
    return $redirection;
  }
}