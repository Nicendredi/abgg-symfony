<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class invitationServices
{

	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
	  $this->em = $em;
	}

	public function closeInvitation(User $user, $invitation)
	{
		$em = $this->em;
		$invit = $em->getRepository('AppBundle:Player')->findOneBy(array('invitation'=>$invitation));
		dump($invit);

		if (!$invit) {
			throw $this->createNotFoundException('Unable to find Player entity.');
		}

		$invit->setUser($user);
		$invit->setInvitation(null);

		$user->setTeam($invit->getTeam());
		$user->setRole($invit->getRole());		

		$em->persist($invit);
		$em->persist($user);
		$em->flush();

	}


	public function sendInvitation($teamId, $roleId, $user)
	{
		$id = setInvitation($teamId, $roleId);

		sendEmail(2, $id, $user);
	}


	private function setInvitation($teamId, $roleId)
	{
		$invitationId = openssl_random_pseudo_bytes(10);

		$entity = new Player();
		$entity->setTeam($teamId);
		$entity->setTeam($roleId);
		$entity->setInvitation($invitationId);

		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();

		return $invitationId;
	}

}