<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use AppBundle\Entity\RoleRepository;
use AppBundle\Entity\UserRepository;
use AppBundle\Entity\PlayerRepository;
use AppBundle\Entity\User;
use AppBundle\Services\CheckRoleTeamApplication;
use AppBundle\Form\ContainerAwareType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CapitainType extends AbstractType
{
	protected $teamId;
	
	public function __construct ($teamId)
	{
	    $this->teamId = $teamId;
	}
	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$teamId=$this->teamId;
		
        $builder
        ->add('captain','entity', array(
			'required' => true,
		    'class' => 'AppBundle:User',
		    'query_builder' => function (UserRepository $er) use ($teamId)
		    {
		        return $er->getTeamId($teamId);
		    },
		    'choice_label' => 'username',
		));
    }
	
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Team',
            'cascade_validation' => true,
        ));
    }
	

    /**
     * @return string
     */
    public function getName()
    {
        return 'team';
    }
}
