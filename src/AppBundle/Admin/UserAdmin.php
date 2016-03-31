<?php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('username', 'text');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        	->add('username')
        	->add('firstName')
        	->add('lastName')
        	->add('email')
        	->add('experience.username')
		;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        	->addIdentifier('username')
        	->addIdentifier('firstName')
        	->addIdentifier('lastName')
        	->addIdentifier('email')
        	->addIdentifier('birth')
        	->addIdentifier('experience.username')
        	->addIdentifier('team.name')
		;
    }
}