<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use AppBundle\Entity\RoleRepository;
use AppBundle\Entity\Player;

class TeamType extends AbstractType
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
        ->add('name')
		->add('player', 'collection', array(
		'type'  => new PlayerType($gameId,$game),
        'allow_add'   => true,
        'allow_delete'=> true,
        'required' => false,
        'label' => false));
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
