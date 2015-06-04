<?php

namespace Fenrizbes\PublicationsBundle\Admin;

use Fenrizbes\PublicationsBundle\Model\Publication;
use Fenrizbes\PublicationsBundle\Model\PublicationQuery;
use Fenrizbes\PublicationsBundle\Model\PublicationType;
use Fenrizbes\PublicationsBundle\Model\PublicationTypeQuery;
use Knp\Menu\MenuItem;
use Propel\PropelBundle\Validator\Constraints\UniqueObject;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class PublicationAdmin extends Admin
{
    protected $baseRouteName    = 'fenrizbes_publication';
    protected $baseRoutePattern = '/fenrizbes/publication/{publication_type_key}';

    /**
     * @var PublicationType
     */
    protected $publication_type;

    /**
     * @return PublicationType
     */
    public function getPublicationType()
    {
        if (is_null($this->publication_type)) {
            $this->publication_type = PublicationTypeQuery::create()->findPk(
                $this->getRequest()->get('publication_type_key')
            );
        }

        return $this->publication_type;
    }

    /**
     * {@inheritdoc}
     */
    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        $parameters = array_merge(array(
            'publication_type_key' => $this->getPublicationType()->getKey()
        ), $parameters);

        return parent::generateUrl($name, $parameters, $absolute);
    }

    /**
     * {@inheritdoc}
     */
    public function getBreadcrumbs($action)
    {
        $breadcrumbs = parent::getBreadcrumbs($action);

        $menu = $this->menuFactory->createItem('root');
        $menu = $menu->addChild(
            $this->trans($this->getLabelTranslatorStrategy()->getLabel('publication_type_list', 'breadcrumb', 'link')),
            array('uri' => $this->routeGenerator->generate('fenrizbes_publication_type_list'))
        );

        array_splice($breadcrumbs, 1, 0, array($menu));

        /** @var MenuItem $banner_list_menu */
        $banner_list_menu = $breadcrumbs[2];
        $banner_list_menu->setName(
            $this->trans($this->getLabelTranslatorStrategy()->getLabel(
                sprintf('%s_list', $this->getClassnameLabel()), 'breadcrumb', 'link'
            )) .' "'. $this->getPublicationType()->getTitle() .'"'
        );

        return $breadcrumbs;
    }

    /**
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        /** @var PublicationQuery $query */
        $query = parent::createQuery($context);

        $query->filterByPublicationType($this->getPublicationType());

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        $subject = parent::getSubject();

        if ($subject instanceof Publication) {
            $subject->setPublicationType($this->getPublicationType());
        }

        return $subject;
    }

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
                    ->add('CreatedAt', 'datetime_no_intl_picker', array(
                        'label'    => 'fp.label.admin.publication.created_at',
                        'format'   => $this->datetime_format,
                        'required' => false
                    ))
                ->end()
            ->end()

            ->setHelps(array(
                'Slug'         => 'fp.label.admin.hint.slug',
                'Announcement' => 'fp.label.admin.hint.announcement',
                'CreatedAt'    => 'fp.label.admin.hint.created_at'
            ))
        ;
    }

    /**
     * @param Publication $object
     * @return mixed|void
     */
    public function preUpdate($object)
    {
        if (is_null($object->getCreatedAt())) {
            $object->setCreatedAt(new \DateTime());
        }
    }
}
