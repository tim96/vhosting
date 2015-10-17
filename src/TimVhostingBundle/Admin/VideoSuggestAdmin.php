<?php

namespace TimVhostingBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use TimConfigBundle\Admin\Base\BaseAdmin;

class VideoSuggestAdmin extends BaseAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title', null, array('label' => 'Title'))
            ->add('tags', null, array('label' => 'Tags'))
            ->add('userName', null, array('label' => 'Username'))
            ->add('email', null, array('label' => 'Email'))
            ->add('link', null, array('label' => 'Link'))
            ->add('description', null, array('label' => 'Description'))
            ->add('createdAt', null, array('label' => 'Created'))
        ;

        parent::configureDatagridFilters($datagridMapper);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);

        $listMapper
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
            ->addIdentifier('id')
            ->add('title', null, array('label' => 'Title'))
            ->add('tags', null, array('label' => 'Tags'))
            ->add('userName', null, array('label' => 'Username'))
            ->add('email', null, array('label' => 'Email'))
            ->add('link', null, array('label' => 'Link'))
            ->add('description', null, array('label' => 'Description'))
            ->add('createdAt', null, array('label' => 'Created'))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Title'))
            ->add('userName', null, array('label' => 'Username'))
            ->add('email', null, array('label' => 'Email'))
            ->add('link', null, array('label' => 'Link'))
            ->add('description', null, array('label' => 'Description'))
            ->add('tags', 'sonata_type_model', array(
                'by_reference' => false, 'multiple' => true, 'required' => true,
                'label' => 'Tags')
            )
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title', null, array('label' => 'Title'))
            ->add('userName', null, array('label' => 'Username'))
            ->add('email', null, array('label' => 'Email'))
            ->add('link', null, array('label' => 'Link',
                'template' => 'TimVhostingBundle:VideoSuggest:link_show.html.twig'))
            ->add('description', null, array('label' => 'Description'))
            ->add('tags', null, array('label' => 'Tags'))
            ->add('createdAt', null, array('label' => 'Created'))
        ;
    }
}
