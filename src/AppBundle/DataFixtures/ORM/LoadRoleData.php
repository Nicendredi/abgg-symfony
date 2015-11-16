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
        $role1 = new Role();
        $role1->setName('Top Lane');
        $role1->setSystName('top');
        $role1->setGame($this->getReference('lol-game'));

        $manager->persist($role1);


        $role1 = new Role();
        $role1->setName('Middle Lane');
        $role1->setSystName('mid');
        $role1->setGame($this->getReference('lol-game'));

        $manager->persist($role1);

        $role1 = new Role();
        $role1->setName('Bottom Lane');
        $role1->setSystName('bot');
        $role1->setGame($this->getReference('lol-game'));

        $manager->persist($role1);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}
