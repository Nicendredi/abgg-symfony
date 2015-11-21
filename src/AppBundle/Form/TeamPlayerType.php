<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use AppBundle\Entity\RoleRepository;

class TeamPlayerType extends AbstractType
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
	
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_teamplayer';
    }
}
