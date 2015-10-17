<?php

namespace TimVhostingBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use TimConfigBundle\Admin\Base\BaseAdmin;

class FeedbackAdmin extends BaseAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('email', null, array('label' => 'Email'))
            ->add('name', null, array('label' => 'Username'))
            ->add('message', null, array('label' => 'Message'))
            ->add('createdAt', null, array('label' => 'Created'))
            ->add('isAnswered', null, array('label' => 'Is Answer?'))
        ;

        parent::configureDatagridFilters($datagridMapper);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
            ->addIdentifier('id')
            ->add('email', null, array('label' => 'Email'))
            ->add('name', null, array('label' => 'Username'))
            ->add('message', null, array('label' => 'Message'))
            ->add('createdAt', null, array('label' => 'Created'))
            ->add('isAnswered', null, array('label' => 'Is Answer?'))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('email', null, array('label' => 'Email'))
            ->add('name', null, array('label' => 'Username'))
            ->add('message', null, array('label' => 'Message'))
            ->add('isAnswered', null, array('label' => 'Is Answer?'))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('email', null, array('label' => 'Email'))
            ->add('name', null, array('label' => 'Username'))
            ->add('message', null, array('label' => 'Message'))
            ->add('createdAt', null, array('label' => 'Created'))
            ->add('isAnswered', null, array('label' => 'Is Answer?'))
        ;
    }
}
