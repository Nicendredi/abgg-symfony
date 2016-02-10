<?php
namespace AppBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Application;
use AppBundle\Entity\Player;
use AppBundle\Entity\Validation;
use AppBundle\Entity\Team;

class ApplicationListener
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var StatusHistoryHandler
     */
    protected $statusHistoryHandler;

    /**
     * @var InsertingFile
     */
    protected $insertingFile;

    public function __construct(ContainerInterface $container)
    {
        // We use container directly in order to avoid a CircularReferenceException
        $this->container = $container;
    }

    public function init()
    {
        $this->em = $this->container->get('doctrine')->getManager();
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->init();
        $entity = $args->getEntity();
        if ($entity instanceof Application)
        {
            $this->checkMultipleApplication($entity);
        }
		elseif ($entity instanceof Player) 
		{
			$this->blockApplication($entity);
		}
		elseif ($entity instanceof Validation) 
		{
			$this->setWaitingList($entity);
		
		}
    }
	
	public function setWaitingList(Validation $validation)
	{
        $query = $this->em->createQuery(
		    'SELECT t
		    FROM AppBundle:Team t
		    INNER JOIN AppBundle:Validation v
		    WITH t.validation=v.id
		    WHERE v.payed is not null
		    and t.tournament = '.$validation->getTeam()->getTournament()->getId()
		);
		$payedTeams = $query->getResult();
		
		if(count($payedTeams)<=16)
		{
			$validation->setPayed(1);
			$this->em->persist($validation);
			$this->em->flush();
		}
		else{
			$validation->setPayed(0);
			$this->em->persist($validation);
			$this->em->flush();
		}
	}

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->init();
        $entity = $args->getEntity();
        if ($entity instanceof Application) {
            $this->checkMultipleApplication($entity);
        }
    }
	
	public function checkMultipleApplication(Application $application)
	{
		$team = $application->getTeam()->getId();
		if($application->getUser())
		{
			$user = ' and p.user = '.($application->getUser()->getId());
		}
		else {
			$user='';
		}
		
		if($application->getRole())
		{
			$role = 'and p.role = '.($application->getRole()->getId());
		}
		else {
			$role='';
		}
		
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.team = '.$team.$user.$role.
		    'and p.origin=\'team\'
		    and p.blocked is null'
		);
		$appTeams = $query->getResult();
		
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.team = '.$team.$user.$role.
		    'and p.origin=\'player\'
		    and p.blocked is null'
		);
		$appPlayers = $query->getResult();
		
		if($appTeams && $appPlayers)
		{
			foreach ($appTeams as $appTeam) 
			{
				foreach($appPlayers as $appPlayer)
				{
					if(($appTeam->getTeam()==$appPlayer->getTeam())
					&& ($appTeam->getUser()==$appPlayer->getUser())
					&& ($appTeam->getRole()==$appPlayer->getRole()))
					{
						$player = new Player();
						$player->setTeam($appTeam->getTeam());
						$player->setUser($appTeam->getUser());
						if($appTeam->getRole())
						{
							$player->setRole($appTeam->getRole());
						}
						$this->em->persist($player);
						
						$user = $player->getUser();
						$user->setPlayer($player);
						$user->setTeam($player->getTeam());
						$user->setRole($player->getRole());
						
            			$this->container->get('fos_user.user_manager')->updateUser($user, false);
						
						$this->em->flush();
						return;
					}
				}
			}
		}
	}

	public function blockApplication(Player $player)
	{
		
		$team = $player->getTeam()->getId();
		$user = $player->getUser()->getId();
		
		if($player->getRole())
		{
			$role = $player->getRole()->getId();
		}
		
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.user = '.$user.'
		    and p.blocked is null'
		);
		$appUsers = $query->getResult();
		
		foreach($appUsers as $appUser)
		{
			if($appUser->getBlocked()==0)
			{
				$appUser->setBlocked(1);
				$this->em->persist($appUser);
			}
		}
		
		if($player->getRole())
		{
	        $query = $this->em->createQuery(
			    'SELECT p
			    FROM AppBundle:Application p
			    WHERE p.team = '.$team.
			    'and p.role = '.$role.'
		    	and p.blocked is null'
			);
			$appTeams = $query->getResult();
			
			foreach($appTeams as $appTeam)
			{
				if($appTeam->getBlocked()==0)
				{
					$appTeam->setBlocked(1);
					$this->em->persist($appTeam);
				}
			}
		}
		$this->em->flush();
	}
}