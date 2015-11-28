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

class ApplicationType extends AbstractType
{
	protected $gameId;
	protected $game;
	protected $team;
	protected $user;
	protected $origin;

	public function __construct ($gameId,$game, $team, $user, $origin)
	{
	    $this->gameId = $gameId;
	    $this->game = $game;
	    $this->team = $team;
	    $this->user = $user;
	    $this->origin = $origin;
	}
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$gameId=$this->gameId;
		$game=$this->game;
		$team=$this->team;
		$user=$this->user;
		$origin=$this->origin;

        $builder
        ->add('user', 'hidden', array('data' => $user))
        ->add('origin', 'hidden', array('data' => $origin))
        ->add('Email', 'email');
			
		if ($game == 'League of Legends')
		{
			$builder
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
    }
	
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Application',
            'cascade_validation' => true,
        ));
    }
	

    /**
     * @return string
     */
    public function getName()
    {
        return 'application';
    }
}
