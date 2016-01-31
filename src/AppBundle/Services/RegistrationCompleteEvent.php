<?php

namespace AppBundle\Services;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\User;

class RegistrationCompleteEvent extends Event
{
	protected $user;

	public function __construct(User $user){
		$this->user = $user;
	}

	public function getUser(){
		return $this->user;
	}

}