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

	public function sendEmail($type, array $args)
	{
		switch ($type) {
			case 1:
				sendRegistration($args);
				break;
			case 2:
				sendIntivation($args);
				break;
		}

	}

	private function sendIntivation(array $args)
	{
		$twig = $this->get('twig');
		$template = $twig->loadTemplate('invitation.twig.html');
		$parameters = $args;

		$message=\Swift_Message::newInstance()
		->setSubject("Invitation de "+$args->user)
		->setFrom("contact@asso-b2g.com")
		->setCc("contact@asso-b2g.com")
		->setTo($args->user->getEmail())
		->setBody(
			$this->renderView(
				'Ressources/mail/mailTemplate/invitation.html.twig', 
				array('user' => $args->user,
					'team' => $args->team,
					'inviToken' => $args->id,
					)
				)
			)
		;

		$this->get('mailer')->send($message);
	}



	private function sendRegistration(User $user)
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