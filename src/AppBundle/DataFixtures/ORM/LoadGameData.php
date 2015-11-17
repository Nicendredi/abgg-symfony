<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Game;

class LoadGameData  extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $lol = new Game();
        $lol->setName('League of Legends');
        $lol->setSystName('lol');
        $lol->setNbPlayers(5);

        $manager->persist($lol);

        $csgo = new Game();
        $csgo->setName('Counter Strike : Global Offensive');
        $csgo->setSystName('csgo');
        $csgo->setNbPlayers(5);

        $manager->persist($csgo);


        $manager->flush();

        $this->addReference('lol-game', $lol);
        $this->addReference('csgo-game', $csgo);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}
