<?php

namespace TimVhostingBundle\Admin;

// use Sonata\AdminBundle\Admin\Admin;
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
            ->add('title')
            ->add('tags')
            ->add('userName')
            ->add('email')
            ->add('link')
            ->add('description')
            ->add('createdAt')
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
            ->add('id')
            ->add('title')
            ->add('tags')
            ->add('userName')
            ->add('email')
            ->add('link')
            ->add('description')
            ->add('createdAt')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            // ->add('id')
            ->add('title')
            ->add('userName')
            ->add('email')
            ->add('link')
            ->add('description')
            ->add('tags', null, array('multiple' => true))
            // ->add('createdAt')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('userName')
            ->add('email')
            ->add('link')
            ->add('description')
            ->add('tags')
            ->add('createdAt')
        ;
    }
}
