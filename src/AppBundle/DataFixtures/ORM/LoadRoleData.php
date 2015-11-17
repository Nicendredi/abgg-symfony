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
        $role = new Role();
        $role->setName('Top Lane');
        $role->setSystName('lol_top');
        $role->setGame($this->getReference('lol-game'));

        $manager->persist($role);

        $role = new Role();
        $role->setName('Middle Lane');
        $role->setSystName('lol_mid');
        $role->setGame($this->getReference('lol-game'));

        $manager->persist($role);

        $role = new Role();
        $role->setName('Bottom Carry');
        $role->setSystName('lol_bot');
        $role->setGame($this->getReference('lol-game'));

        $manager->persist($role);

        $role = new Role();
        $role->setName('Support');
        $role->setSystName('lol_sup');
        $role->setGame($this->getReference('lol-game'));

        $manager->persist($role);

        $role = new Role();
        $role->setName('Jungle');
        $role->setSystName('lol_jun');
        $role->setGame($this->getReference('lol-game'));

        $manager->persist($role);

        $role = new Role();
        $role->setName('Entry Fragger');
        $role->setSystName('csg_ent');
        $role->setGame($this->getReference('csgo-game'));

        $manager->persist($role);

        $role = new Role();
        $role->setName('Playmaker');
        $role->setSystName('csg_pla');
        $role->setGame($this->getReference('csgo-game'));

        $manager->persist($role);

        $role = new Role();
        $role->setName('Strat Caller');
        $role->setSystName('csg_str');
        $role->setGame($this->getReference('csgo-game'));

        $manager->persist($role);

        $role = new Role();
        $role->setName('Support');
        $role->setSystName('csg_sup');
        $role->setGame($this->getReference('csgo-game'));

        $manager->persist($role);

        $role = new Role();
        $role->setName('Awper');
        $role->setSystName('csg_awp');
        $role->setGame($this->getReference('csgo-game'));

        $manager->persist($role);

        $role = new Role();
        $role->setName('Lurker');
        $role->setSystName('csg_lur');
        $role->setGame($this->getReference('csgo-game'));

        $manager->persist($role);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}
