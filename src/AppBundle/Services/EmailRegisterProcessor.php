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

	public function sendRegistration(User $user)
	{
		$message = \Swift_Message::newInstance()
		->setSubject("Registration complete")
		->setFrom("contact@asso-b2g.com")
		->setTo($user->getEmail())
		->setContentType("text/html")
		->setBody(
			$this->templating->render(
				'AppBundle:mail:registerComplete.html.twig', 
				array('user' => $user
					)
				)
			)
		;
		$this->mailer->send($message);
	}
	
	public function sendMail($responsable,$email,$object,$mail)
	{
		$message = \Swift_Message::newInstance()
		->setSubject($object)
		->setFrom($email)
		->setTo($responsable)
		->setBody($mail);
		$this->mailer->send($message);
	}
}