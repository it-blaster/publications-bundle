<?php

namespace Fenrizbes\PublicationsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints\Length;

class PublicationTypeAdmin extends Admin
{
    protected $baseRouteName    = 'fenrizbes_publication_type';
    protected $baseRoutePattern = '/fenrizbes/publication';

    /**
     * {@inheritdoc}
     */
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by'    => 'Title'
    );

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);

        $collection
            ->remove('create')
            ->remove('edit')
            ->remove('show')
            ->remove('export')
            ->remove('delete')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('Title', null, array(
                'label'    => 'fp.label.admin.publication_type.title',
                'template' => 'FenrizbesPublicationsBundle:Admin/PublicationType:list_title.html.twig'
            ))
            ->add('Key', null, array(
                'label' => 'fp.label.admin.publication_type.key'
            ))
        ;
    }
}
