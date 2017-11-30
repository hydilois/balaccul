<?php

namespace UserBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\User;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RedirectUserListener{
    private $tokenStorage;
    private $router;

    public function __construct(TokenStorageInterface $t, RouterInterface $r){
        $this->tokenStorage = $t;
        $this->router = $r;
    }

    public function onKernelRequest(GetResponseEvent $event){
        if ($this->isUserLogged() && $event->isMasterRequest()) {
            $currentRoute = $event->getRequest()->attributes->get('_route');
            if ($this->isAuthenticatedUserOnAnonymousPage($currentRoute)) {
                $response = new RedirectResponse($this->router->generate('homepage'));
                $event->setResponse($response);
            }
        }
    }

    
    private function isUserLogged(){
        $token = $this->tokenStorage->getToken();
        if ($token) {
            $user = $token->getUser();
            return $user instanceof User;
        }else {
            return false;
        }

        return false;//this should be returned cause a user not connected can access anonymous pages
    }

    private function isAuthenticatedUserOnAnonymousPage($currentRoute){
        return in_array(
            $currentRoute,
            ['fos_user_security_login', 'fos_user_resetting_request']
        );
    }
}