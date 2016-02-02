<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use AppBundle\Entity\RoleRepository;
use AppBundle\Form\PostesType;

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
            ->add('username','text', array(
				'label'=> 'Pseudo In-Game'
			));
			
		if ($game == 'League of Legends')
		{
			$builder
            ->add('postes', new PostesType(), array(
				'label'=> 'Donnez une note de 1 (meilleur) à 5 (pas maitrisé) sur votre maitrise d\'un poste : '
				));
            
		}
		else {
			$builder
				-> add('steam', 'text', array(
					'label'=> 'Pseudo Steam'
				));
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
