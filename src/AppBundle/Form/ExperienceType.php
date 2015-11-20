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

	public function __construct ($gameId)
	{
	    $this->gameId = $gameId;
	}
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$gameId=$this->gameId;
		
        $builder
            ->add('lookingForTeam')
            ->add('username')
            ->add('role_1','entity', array(
			    'class' => 'AppBundle:Role',
			    'query_builder' => function (RoleRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name',
			))
            ->add('role_2','entity', array(
			    'class' => 'AppBundle:Role',
			    'query_builder' => function (RoleRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name',
			))
            ->add('role_3','entity', array(
			    'class' => 'AppBundle:Role',
			    'query_builder' => function (RoleRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name',
			))
            ->add('role_4','entity', array(
			    'class' => 'AppBundle:Role',
			    'query_builder' => function (RoleRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name',
			))
            ->add('role_5','entity', array(
			    'class' => 'AppBundle:Role',
			    'query_builder' => function (RoleRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name',
			))
			->add('ranking','entity', array(
				//'data' => $ranking,
			    'class' => 'AppBundle:Ranking',
			    'query_builder' => function (RankingRepository $er) use ($gameId)
			    {
			        return $er->getGameId($gameId);
			    },
			    'choice_label' => 'name',
			    'expanded' =>true
			))
            ->add('underRanking','entity', array(
			    'class' => 'AppBundle:UnderRanking',
			    //'query_builder' => function (UnderRankingRepository $er) use ($gameId,$this)
			    //{
			    //    return $er->getGameId($gameId,$this);
			    //},
			   'choice_label' => 'name',
			));
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
