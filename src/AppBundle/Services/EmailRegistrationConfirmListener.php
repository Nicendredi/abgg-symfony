<?php

namespace AppBundle\Services;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\User;



class EmailRegistrationConfirmListener
{
    private $processor;

    public function __construct(emailRegisterProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function onRegistrationComplete(RegistrationCompleteEvent $event)
    {
        $this->processor->sendRegistration( $event->getUser());
    }


}
