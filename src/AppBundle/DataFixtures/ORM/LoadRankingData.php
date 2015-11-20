<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Ranking;
use AppBundle\Entity\UnderRanking;

class LoadRankingData  extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {	
		static $list=array(
			'Bronze' => 'lol-game',
			'Argent'=> 'lol-game',
			'Or'=> 'lol-game',
			'Platine'=> 'lol-game',
			'Diamant'=> 'lol-game',
			'Silver'=> 'csgo-game',
			'Gold Nova' => 'csgo-game',
			'Master Guardian' => 'csgo-game',
			'Legendary Eagle' => 'csgo-game',
			'Supreme First Class' => 'csgo-game',
			'The Global' => 'csgo-game'
		);
		
		foreach($list as $data=>$reference)
		{
	        $ranking = new Ranking();
	        $ranking->setName($data);
        	$ranking->setGame($this->getReference($reference));
	        $manager->persist($ranking);
	        $manager->flush();
			$this->addReference($data,$ranking);
		}
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}
