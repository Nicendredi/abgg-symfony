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
			'Silver I'=> 'csgo-game',
			'Silver II'=> 'csgo-game',
			'Silver III'=> 'csgo-game',
			'Silver IV'=> 'csgo-game',
			'Master Silver'=> 'csgo-game',
			'Nova I' => 'csgo-game',
			'Nova II' => 'csgo-game',
			'Nova III' => 'csgo-game',
			'Nova IV' => 'csgo-game',
			'Master Guardian (Mg)' => 'csgo-game',
			'Master Guardian 2 (Mg2)' => 'csgo-game',
			'Master Guardian Elite (MgE)' => 'csgo-game',
			'Legendary Eagle (LE)' => 'csgo-game',
			'Legendary Eagle Master (LEM)' => 'csgo-game',
			'Supreme Master First Class' => 'csgo-game',
			'The Global Elite' => 'csgo-game'
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
