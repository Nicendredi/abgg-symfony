<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;


class EmailRegisterProcessor
{
	
	protected $mailer;

	public function __construct(\Swift_Mailer $mailer)
	{
		$this->mailer = $mailer;
	}

	public function sendEmailRegistration(User $user)
	{
		$message = \Swift_Message::newInstance()
		->setSubject("Registration complete")
		->setFrom("contact@asso-b2g.com")
		->setTo("zibo09@gmail.com"/*$user->getEmail()*/)
		->setBody("Test test, this is a Test ! I love you my Nana. Kissous kissous calinou calinou. =D <3")
		->attach(\Swift_Attachment::fromPath('C:\wamp\www\abgg-symfony\web\ressources\Keurkeur.pdf'));

		$this->mailer->send($message);
	}
}