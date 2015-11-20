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
			'I' => array(
				'Bronze', 
				'Argent', 
				'Or',
				'Platine',
				'Diamant',
				'Silver',
				'Gold Nova',
				'Master Guardian'
			),
			'II' => array(
				'Bronze', 
				'Argent', 
				'Or',
				'Platine',
				'Diamant',
				'Silver',
				'Gold Nova',
				'Master Guardian'
			),
			'III' => array(
				'Bronze', 
				'Argent',
				'Or',
				'Platine',
				'Diamant',
				 'Silver',
				 'Gold Nova'
			),
			'IV' => array(
				'Bronze', 
				'Argent', 
				'Or',
				'Platine',
				'Diamant',
				'Silver'
			),
			'V' => array(
				'Bronze', 
				'Argent', 
				'Or',
				'Platine',
				'Diamant',
				
			),
			'Elite' => array(
				'Silver',
				'Master Guardian',
				'The Global'
			),
			'Elite Master' => array(
				'Silver'
			),
			'Master' => array(
				'Gold Nova',
				'Legendary Eagle',
				'Supreme First Class'
			),
			'Distinguished' => array(
				'Master Guardian'
			)
		);
		
		foreach($list as $data=>$ranking)
		{
	        $underRanking = new UnderRanking();
	        $underRanking->setName($data);
			foreach($ranking as $reference)
			{
	        	$underRanking->addRanking($this->getReference($reference));
	        	$manager->persist($underRanking);
			}
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
