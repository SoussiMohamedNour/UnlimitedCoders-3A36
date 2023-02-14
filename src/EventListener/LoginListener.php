<?php
namespace App\EventListener;

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
        // get the security token of the session that is about to be logged out
        $token = $event->getAuthenticatedToken();

        $user = $token->getUser();
        // $response= new RedirectResponse($this->urlGenerator->generate($user->getRoles()[0]));


        // //  var_dump($user->getRoles()); 
        // var_dump(print('degla'));
        // print('delga');

        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            $response= new RedirectResponse($this->urlGenerator->generate('app_admin'));
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

        
        // return new RedirectResponse($this->urlGenerator->generate('home'));
        $event->setResponse($response);
    }
    
}

