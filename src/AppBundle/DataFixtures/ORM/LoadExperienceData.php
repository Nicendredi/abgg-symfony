<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Experience;

class LoadExperienceData  extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $exp = new Experience();
        $exp->setLookingForTeam(true);
        $exp->setRankActual('Silver I');
        $exp->setUsername('xXKira29Xx');
        $exp->setRole1($this->getReference('role'));
        $exp->setRole2($this->getReference('role1'));
        $exp->setRole3($this->getReference('role2'));
        $exp->setRole4($this->getReference('role3'));
        $exp->setRole5($this->getReference('role4'));

        $manager->persist($exp);

        $exp1 = new Experience();
        $exp1->setLookingForTeam(false);
        $exp1->setRankActual('Silver V');
        $exp1->setUsername('xXKillerDu33LeBoGoSsXx');
        $exp1->setRole1($this->getReference('role11'));
        $exp1->setRole2($this->getReference('role10'));
        $exp1->setRole3($this->getReference('role5'));
        $exp1->setRole4($this->getReference('role6'));
        $exp1->setRole5($this->getReference('role7'));

        $manager->persist($exp1);

        $manager->flush();

        $this->addReference('kira', $exp);
        $this->addReference('killer', $exp1);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 3;
    }
}
