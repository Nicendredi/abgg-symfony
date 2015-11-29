<?php
namespace AppBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Application;
use AppBundle\Entity\Player;

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
		$user = $application->getUser()->getId();
		
		if($application->getRole())
		{
			$role = $application->getRole()->getId();
		}
		else {
			$role='';
		}
		
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.team = '.$team.
		    'and p.user = '.$user.
		    'and p.role = '.$role.
		    'and p.origin=\'team\''
		);
		$appTeams = $query->getResult();
		
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.team = '.$team.
		    'and p.user = '.$user.
		    'and p.role = '.$role.
		    'and p.origin=\'player\''
		);
		$appPlayers = $query->getResult();
		
		if($appTeams && $appPlayers)
		{
			foreach ($appTeams as $appTeam) 
			{
				foreach($appPlayers as $appPlayer)
				{
					if($appTeam==$appPlayer)
					{
						$player = new Player();
						$player->setTeam($appTeam->getTeam());
						$player->setUser($appTeam->getUser());
						if($appTeam->getRole())
						{
							$player->setRole($appTeam->getRole());
						}
						$this->em->persist($player);
					}
				}
			}
		}
		$this->em->flush();
	}
}