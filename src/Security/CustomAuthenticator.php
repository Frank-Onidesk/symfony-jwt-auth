<?php
namespace App\Security;

use ApiPlatform\OpenApi\Model\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CustomAuthenticator extends JWTAuthenticator
{

 

  public function token(Request $request){


    $authorizationHeader = $request->headers->get('Authorization');


    return new Response($authorizationHeader);

  }
}