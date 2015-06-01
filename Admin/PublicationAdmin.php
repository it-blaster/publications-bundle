<?php

namespace Fenrizbes\PublicationsBundle\Admin;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class PublicationAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected $datagridValues = array(
        '_sort_order' => 'desc',
        '_sort_by'    => 'created_at'
    );

    /**
     * The format for datetime fields
     *
     * @var string
     */
    protected $datetime_format;

    /**
     * FormType's name for the content field
     *
     * @var string|null
     */
    protected $content_editor;

    /**
     * Sets the datetime format
     *
     * @param string $format
     */
    public function setDateTimeFormat($format)
    {
        $this->datetime_format = $format;
    }

    /**
     * Sets the content field's type
     *
     * @param string|null $editor
     */
    public function setContentEditor($editor)
    {
        $this->content_editor = $editor;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('PublicationType', null, array(
                'label' => 'fp.label.admin.publication.type'
            ))
            ->add('Title', null, array(
                'label' => 'fp.label.admin.publication.title'
            ))
            ->add('IsPublished', null, array(
                'label' => 'fp.label.admin.publication.is_published'
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('Title', null, array(
                'label' => 'fp.label.admin.publication.title'
            ))
            ->add('PublicationType', null, array(
                'label' => 'fp.label.admin.publication.type'
            ))
            ->add('IsPublished', null, array(
                'label' => 'fp.label.admin.publication.is_published'
            ))
            ->add('CreatedAt', null, array(
                'label'  => 'fp.label.admin.publication.created_at',
                'format' => $this->datetime_format
            ))
            ->add('UpdatedAt', null, array(
                'label'  => 'fp.label.admin.publication.updated_at',
                'format' => $this->datetime_format
            ))
            ->add('_action', 'actions', array(
                'label'    => 'fp.label.admin.actions',
                'sortable' => false,
                'actions'  => array(
                    'edit'   => array(),
                    'delete' => array()
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
            ->tab('fp.label.admin.publication.tab_main')
                ->with('')
                    ->add('PublicationType', null, array(
                        'label'    => 'fp.label.admin.publication.type',
                        'required' => true
                    ))
                    ->add('Title', null, array(
                        'label'       => 'fp.label.admin.publication.title',
                        'constraints' => array(
                            new Length(array(
                                'max'        => 255,
                                'minMessage' => 'fp.constraint.longer_than_255'
                            ))
                        )
                    ))
                    ->add('Content', $this->content_editor, array(
                        'label' => 'fp.label.admin.publication.content'
                    ))
                    ->add('IsPublished', null, array(
                        'label'    => 'fp.label.admin.publication.is_published',
                        'required' => false
                    ))
                ->end()
            ->end()

            ->tab('fp.label.admin.publication.tab_image')
                ->with('')
                    ->add('CroppableImage', 'croppable', array(
                        'translation_domain' => $this->getTranslationDomain(),
                        'label'              => 'fp.label.admin.publication.image',
                        'width'              => 140,
                        'height'             => 95
                    ))
                ->end()
            ->end()

            ->tab('fp.label.admin.publication.tab_additional')
                ->with('')
                    ->add('Slug', null, array(
                        'label' => 'fp.label.admin.publication.slug',
                        'constraints' => array(
                            new Regex(array(
                                'pattern' => '/^[\w\-]+$/',
                                'message' => 'fp.constraint.not_alphanumeric'
                            ))
                        )
                    ))
                    ->add('Announcement', null, array(
                        'label' => 'fp.label.admin.publication.announcement'
                    ))
                ->end()
            ->end()

            ->setHelps(array(
                'Slug'         => 'fp.label.admin.hint.slug',
                'Announcement' => 'fp.label.admin.hint.announcement'
            ))
        ;
    }
}
