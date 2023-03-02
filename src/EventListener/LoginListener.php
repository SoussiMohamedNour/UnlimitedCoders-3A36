<?php
namespace App\EventListener;

use App\Entity\Utilisateur;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginListener implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
       
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onLoginSuccess'];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
         
        $token = $event->getAuthenticatedToken();
        $user = $token->getUser();
        
       
        
        if ($user instanceof Utilisateur && $user->isIsbanned())
        {
            $response = new RedirectResponse($this->urlGenerator->generate('app_banned'));
        }

        elseif (in_array("ROLE_ADMIN", $user->getRoles())) {
            $response= new RedirectResponse($this->urlGenerator->generate('app_utilisateur_index'));
        }
        elseif (in_array("ROLE_MEDECIN", $user->getRoles())) {
            $response= new RedirectResponse($this->urlGenerator->generate('app_medecin'));
        }

        elseif (in_array("ROLE_PHARMACIEN", $user->getRoles())) {
            $response= new RedirectResponse($this->urlGenerator->generate('app_pharmacien'),
        RedirectResponse::HTTP_SEE_OTHER);
        }
 
        elseif (in_array("ROLE_USER", $user->getRoles())) {
            $response= new RedirectResponse($this->urlGenerator->generate('app_home'),
        RedirectResponse::HTTP_SEE_OTHER);
        }

        

        $event->setResponse($response);
    }
    
}

