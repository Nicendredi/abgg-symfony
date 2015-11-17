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

        $role1 = new Role();
        $role1->setName('Middle Lane');
        $role1->setSystName('lol_mid');
        $role1->setGame($this->getReference('lol-game'));

        $manager->persist($role1);

        $role2 = new Role();
        $role2->setName('Bottom Carry');
        $role2->setSystName('lol_bot');
        $role2->setGame($this->getReference('lol-game'));

        $manager->persist($role2);

        $role3 = new Role();
        $role3->setName('Support');
        $role3->setSystName('lol_sup');
        $role3->setGame($this->getReference('lol-game'));

        $manager->persist($role3);

        $role4 = new Role();
        $role4->setName('Jungle');
        $role4->setSystName('lol_jun');
        $role4->setGame($this->getReference('lol-game'));

        $manager->persist($role4);

        $role5 = new Role();
        $role5->setName('Entry Fragger');
        $role5->setSystName('csg_ent');
        $role5->setGame($this->getReference('csgo-game'));

        $manager->persist($role5);

        $role6 = new Role();
        $role6->setName('Playmaker');
        $role6->setSystName('csg_pla');
        $role6->setGame($this->getReference('csgo-game'));

        $manager->persist($role6);

        $role7 = new Role();
        $role7->setName('Strat Caller');
        $role7->setSystName('csg_str');
        $role7->setGame($this->getReference('csgo-game'));

        $manager->persist($role7);

        $role8 = new Role();
        $role8->setName('Support');
        $role8->setSystName('csg_sup');
        $role8->setGame($this->getReference('csgo-game'));

        $manager->persist($role8);

        $role10 = new Role();
        $role10->setName('Awper');
        $role10->setSystName('csg_awp');
        $role10->setGame($this->getReference('csgo-game'));

        $manager->persist($role10);

        $role11 = new Role();
        $role11->setName('Lurker');
        $role11->setSystName('csg_lur');
        $role11->setGame($this->getReference('csgo-game'));

        $manager->persist($role11);

        $manager->flush();

        $this->addReference('role', $role);
        $this->addReference('role1', $role1);
        $this->addReference('role2', $role2);
        $this->addReference('role3', $role3);
        $this->addReference('role4', $role4);
        $this->addReference('role5', $role5);
        $this->addReference('role6', $role6);
        $this->addReference('role7', $role7);
        $this->addReference('role8', $role8);
        $this->addReference('role10', $role10);
        $this->addReference('role11', $role11);

    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}
