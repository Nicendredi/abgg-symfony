<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use AppBundle\Entity\RoleRepository;
use AppBundle\Entity\UserRepository;
use AppBundle\Entity\User;
use AppBundle\Services\CheckRoleTeamApplication;
use AppBundle\Form\ContainerAwareType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CaptainType extends AbstractType
{
	protected $gameId;
	protected $game;
	protected $telephone;
	protected $captain;
	
	public function __construct ($gameId,$game,$telephone,$captain=null, $container=null)
	{
	    $this->gameId = $gameId;
	    $this->game = $game;
	    $this->telephone = $telephone;
	    $this->captain = $captain;
	    $this->container = $container;
	}
	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$gameId=$this->gameId;
		$game=$this->game;
		$telephone=$this->telephone;
		$captain = $this->captain;
		$container = $this->container;
		
		if($container!=null)
		{
			$checkRole = $container -> get('checkRoleService');
			$rolesAvailable = $checkRole -> getFormRoleAvailable($captain->getTeam()->getId(), $captain->getId());
		}
		
		if ($game == 'League of Legends')
		{
			if ($captain == null)
			{
				$builder
				->add('telephone', 'hidden',array('data'=> $telephone))
				->add('role','entity', array(
						'required' => true,
					    'class' => 'AppBundle:Role',
					    'query_builder' => function (RoleRepository $er) use ($gameId)
					    {
					        return $er->getGameId($gameId);
					    },
					    'choice_label' => 'name',
					));
			}
			else 
			{
				$builder
				->add('telephone', 'hidden',array('data'=> $telephone))
				->add('role','entity', array(
						'required' => true,
					    'class' => 'AppBundle:Role',
					    'choices'=> $rolesAvailable,
					    'choice_label' => 'name',
					));
			}
		}
    }
	
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'cascade_validation' => true,
        ));
    }
	

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }
}
