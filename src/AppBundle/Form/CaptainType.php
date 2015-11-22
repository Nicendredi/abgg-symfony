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

class CaptainType extends AbstractType
{
	protected $gameId;
	protected $game;
	protected $telephone;

	public function __construct ($gameId,$game,$telephone)
	{
	    $this->gameId = $gameId;
	    $this->game = $game;
	    $this->telephone = $telephone;
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

		if ($game == 'League of Legends')
		{
			$builder
			->add('telephone', 'hidden',array('data'=> $telephone))
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
