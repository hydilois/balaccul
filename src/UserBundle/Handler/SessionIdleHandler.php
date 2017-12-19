<?php
namespace UserBundle\Handler;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionIdleHandler{

    protected $session;
    protected $securityToken;
    protected $router;
    protected $maxIdleTime;

    public function __construct(SessionInterface $session, TokenStorageInterface $securityToken, RouterInterface $router, $maxIdleTime = 0)
    {
        $this->session = $session;
        $this->securityToken = $securityToken;
        $this->router = $router;
        $this->maxIdleTime = $maxIdleTime;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {

            return;
        }

        if ($this->maxIdleTime > 0) {

            $this->session->start();
            $lapse = time() - $this->session->getMetadataBag()->getLastUsed();

            if ($lapse > $this->maxIdleTime) {

                $this->securityToken->setToken(null);

                $this->session->getFlashBag()->add('info', 'You are logged out due of your inactivity for a moment.');
                // $this->session->getFlashBag()->add('info', 'Vous êtes déconnecté dû à votre inactivité pour un certain moment.');

                // redirect the user to the login page.
                $event->setResponse(new RedirectResponse($this->router->generate('lock_screen')));
            }
        }
    }

}