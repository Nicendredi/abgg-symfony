<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Player;
use AppBundle\Entity\Application;
use AppBundle\Services\EmailRegisterRegistrator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class invitationServices
{

	public function __construct(\Doctrine\ORM\EntityManager $em, EmailRegisterProcessor $mailer)
	{
	  $this->em = $em;
	  $this->mailer = $mailer;
	}

	public function closeInvitation(User $user, $invitation)
	{
		$em = $this->em;
		$invit = $em->getRepository('AppBundle:Application')->findOneBy(array('invitationToken'=>$invitation));
		dump($invit);

		if (!$invit) {
			throw $this->createNotFoundException('Unable to find Application entity.');
		}

		$invit->setUser($user);
		$invit->setInvitationToken(null);

		$user->setTeam($invit->getTeam());
		$user->setRole($invit->getRole());		

		$em->persist($invit);
		$em->persist($user);
		$em->flush();

	}

	private function createInvitToken(Application $application)
	{
		$invitationToken = rtrim(base64_encode(md5(microtime())),"=");;

		$application->setInvitationToken($invitationToken);

		$em = $this->em;
		$em->persist($application);
		$em->flush();

		return $invitationToken;
	}

	public function sendInvitation(Application $application, User $user)
	{
		$token = $this->createInvitToken($application);
		$this->mailer->sendEmail(2, array(/*'token' =>*/ $token, /*'user' =>*/ $user, $application->getEmail()));
	}
}