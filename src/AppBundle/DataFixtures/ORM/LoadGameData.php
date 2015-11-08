<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Game;

class LoadGameData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $lol = new Game();
        $lol->setName('lol');
        $lol->setNbPlayers(5);
        $lol->setGameRoles(array('top' => 1, 'mid' => 2, 'carry' => 3, 'jungler' => 4,  'support' => 5));

        $manager->persist($lol);

        $csgo = new Game();
        $csgo->setName('csgo');
        $csgo->setNbPlayers(5);
        $csgo->setGameRoles(array('none' => 1));

        $manager->persist($csgo);


        $manager->flush();
    }
}
