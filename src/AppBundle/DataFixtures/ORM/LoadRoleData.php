<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Role;

class LoadRoleData  extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {	
		static $list=array(
			'Top Lane' => 'lol-game',
			'Middle Lane'=> 'lol-game',
			'Bottom Carry'=> 'lol-game',
			'Support '=> 'lol-game',
			'Jungle'=> 'lol-game',
			'Entry Fragger'=> 'csgo-game',
			'Playmaker' => 'csgo-game',
			'Strat Caller' => 'csgo-game',
			'Support' => 'csgo-game',
			'Awper' => 'csgo-game',
			'Lurker' => 'csgo-game',
			'Manager' => '',
			'Remplaçant' => ''
		);
		
		foreach($list as $data=>$reference)
		{
	        $ranking = new Role();
	        $ranking->setName($data);
			if($reference !='')
			{
        		$ranking->setGame($this->getReference($reference));
			}
	        $manager->persist($ranking);
	        $manager->flush();
		}
    }
	
    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}
