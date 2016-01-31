<?php

namespace AppBundle\Services;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Services\BgfesEvents;
use AppBundle\Services\RegistrationCompleteEvent;
use Symfony\Component\HttpFoundation\RequestStack;


class RegistrationConfirmListener implements EventSubscriberInterface
{
    private $router;
	
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        //$event = new RegistrationCompleteEvent($event->getForm()->getViewData());

        //$this->get('event_dispatcher')
        //    ->dispatch(BgfesEvents::onRegistrationComplete, $event)
        //    ;

        $url = $this->router->generate('game_choose');

        $event->setResponse(new RedirectResponse($url));
    }
}
