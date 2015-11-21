<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\UnderRanking;
use AppBundle\Entity\Ranking;

class LoadUnderRankingData  extends AbstractFixture implements OrderedFixtureInterface
{
	
    /**
     * {@inheritDoc}
     */
	public function load(ObjectManager $manager)
	{		
		static $list=array(
			'I' ,
			'II' ,
			'III' ,
			'IV',
			'V'
		);
		
		foreach($list as $data)
		{
	        $underRanking = new UnderRanking();
	        $underRanking->setName($data);
	        $manager->persist($underRanking);
	        $manager->flush();
			$this->addReference($data,$underRanking);
		}
	}

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 3;
    }
}
