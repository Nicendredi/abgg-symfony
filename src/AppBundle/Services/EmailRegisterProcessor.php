<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;


class EmailRegisterProcessor
{
	
	protected $mailer;
	protected $templating;

	public function __construct(ContainerInterface $container)
	{
		$this->mailer = $container->get('mailer');
		$this->templating = $container->get('templating');
	}

	public function sendEmail($type, array $args)
	{
		switch ($type) {
			case 1:
				$this->sendRegistration($args);
				break;
			case 2:
				$this->sendInvitation($args);
				break;
		}

	}

	private function sendInvitation(array $args)
	{
		$token = $args[0];
		$user = $args[1];
		$mail = $args[2];

		$subject = "Invitation de ".$user->getUsername();
		$to = array($mail => $mail);
		$from = array("contact@asso-b2g.com"=>"contact@asso-b2g.com");

		$message=\Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom($from)
		->setTo($to)
		->setBody(
			$this->templating->render(
				'AppBundle:mail:invitation.html.twig', 
				array('user' => $user,
					'team' => $user->getTeam(),
					'token' => $token,
					)
				)
			)
		;

		$this->mailer->send($message);	
	}

	private function sendRegistration(User $user)
	{
		$message = \Swift_Message::newInstance()
		->setSubject("Registration complete")
		->setFrom("contact@asso-b2g.com")
		->setTo($user->getEmail())
		->setBody("Test test, this is a Test ! I love you my Nana. Kissous kissous calinou calinou. =D <3");
		$this->mailer->send($message);
	}
}