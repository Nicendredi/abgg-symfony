<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Entity\RankingRepository;
use AppBundle\Entity\RoleRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ExperienceType extends AbstractType
{
	protected $gameId;
	protected $game;

	public function __construct ($gameId, $game)
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
            ->add('username')
			->add('ranking','entity', array(
				'required' => false,
			    'class' => 'AppBundle:Ranking',
			    'query_builder' => function (RankingRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name'
			));
			
		if ($game == 'League of Legends')
		{
			$builder
            ->add('underRanking','entity', array(
               'required' => false,
			   'class' => 'AppBundle:UnderRanking',
			   'choice_label' => 'name',
			))
            ->add('role_1','entity', array(
				'required' => false,
			    'class' => 'AppBundle:Role',
			    'query_builder' => function (RoleRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name',
			))
            ->add('role_2','entity', array(
				'required' => false,
			    'class' => 'AppBundle:Role',
			    'query_builder' => function (RoleRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name',
			))
            ->add('role_3','entity', array(
				'required' => false,
			    'class' => 'AppBundle:Role',
			    'query_builder' => function (RoleRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name',
			))
            ->add('role_4','entity', array(
				'required' => false,
			    'class' => 'AppBundle:Role',
			    'query_builder' => function (RoleRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name',
			))
            ->add('role_5','entity', array(
				'required' => false,
			    'class' => 'AppBundle:Role',
			    'query_builder' => function (RoleRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name',
			));
		}
		else {
			$builder
				-> add('steam');
		}
    }
	
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Experience'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_experience';
    }
}
