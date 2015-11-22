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

class PlayerType extends AbstractType
{
	protected $gameId;
	protected $game;

	public function __construct ($gameId,$game)
	{
	    $this->gameId = $gameId;
	    $this->game = $game;
	}
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$gameId=$this->gameId;
		$game=$this->game;

        $builder
        ->add('user','entity', array(
				'required' => false,
			    'class' => 'AppBundle:User',
			    'query_builder' => function (UserRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'username',
			));
			
		if ($game == 'League of Legends')
		{
			$builder
			->add('role','entity', array(
					'required' => false,
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
            'data_class' => 'AppBundle\Entity\Player',
            'cascade_validation' => true,
        ));
    }
	

    /**
     * @return string
     */
    public function getName()
    {
        return 'player';
    }
}
