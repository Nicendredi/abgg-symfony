<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setEmail('tournoibgf2015@gmail.com');
        $userAdmin->setEnabled(true);
        $userAdmin->setRoles(array('ROLE_ADMIN'));

        $manager->persist($userAdmin);

        $userNendredi = new User();
        $userNendredi->setUsername('nendredi');
        $userNendredi->setPlainPassword('toto');
        $userNendredi->setEmail('nicolas.endredi@gmail.com');
        $userAdmin->setEnabled(true);


        $manager->persist($userNendredi);

        $manager->flush();
    }
}