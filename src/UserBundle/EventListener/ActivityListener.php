<?php
namespace UserBundle\EventListener;
 
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use UserBundle\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

 
class ActivityListener{

    protected $em;
    private $tokenStorage;
 
    public function __construct(TokenStorageInterface $t, EntityManager $manager){
        $this->tokenStorage = $t;
        $this->em = $manager;
    }
 
    /**
    * Update the user "lastActivity" on each request
    * @param FilterControllerEvent $event
    */
    public function onCoreController(FilterControllerEvent $event){
        // ici nous vérifions que la requête est une "MASTER_REQUEST" pour que les sous-requête soit ingoré (par exemple si vous faites un render() dans votre template)
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }
 
        // Nous vérifions qu'un token d'autentification est bien présent avant d'essayer manipuler l'utilisateur courant.
        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();
 
            // Nous utilisons un délais pendant lequel nous considèrerons que l'utilisateur est toujours actif et qu'il n'est pas nécessaire de refaire de mise à jour
            $delay = new \DateTime();
            $delay->setTimestamp(strtotime('2 minutes ago'));
 
            // Nous vérifions que l'utilisateur est bien du bon type pour ne pas appeler getLastActivity() sur un objet autre objet User
            if ($user instanceof Utilisateur && $user->getLastActivity() < $delay) {
                $user->isActiveNow();
                $this->em->flush($user);
            }
        }
    }
}