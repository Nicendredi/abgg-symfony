<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use AppBundle\Entity\RoleRepository;
use AppBundle\Entity\Player;
use AppBundle\Entity\Team;

class TeamType extends AbstractType
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
		
        $builder
        ->add('name');
		
		if ($game == 'League of Legends')
		{
			$builder
			->add('captain', new CaptainType($gameId,$game,$telephone));
		}
		
		$builder
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
