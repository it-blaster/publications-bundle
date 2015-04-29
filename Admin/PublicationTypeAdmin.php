<?php

namespace Fenrizbes\PublicationsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints\Length;

class PublicationTypeAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
        $collection->remove('batch');
        $collection->remove('delete');
        $collection->remove('create');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('Key', null, array(
                'label' => 'fp.label.admin.publication_type.key'
            ))
            ->add('Title', null, array(
                'label'       => 'fp.label.admin.publication_type.title',
                'constraints' => array(
                    new Length(array(
                        'max'        => 255,
                        'minMessage' => 'fp.constraint.longer_than_255'
                    ))
                )
            ))
            ->add('_action', 'actions', array(
                'label'    => 'fp.label.admin.actions',
                'sortable' => false,
                'actions'  => array(
                    'edit' => array()
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('Title', null, array(
                'label' => 'fp.label.admin.publication_type.title'
            ))
        ;
    }
}
