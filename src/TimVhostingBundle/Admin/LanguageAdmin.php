<?php declare(strict_types = 1);

namespace App\TimVhostingBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use App\TimConfigBundle\Admin\Base\BaseAdmin;

class LanguageAdmin extends BaseAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('updatedAt')
            ->add('createdAt')
            ->add('isDeleted')
            ->add('id')
            ->add('name')
            ->add('code')
        ;

        parent::configureDatagridFilters($datagridMapper);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
            ->add('id')
            ->add('name')
            ->add('code')
            ->add('updatedAt')
            ->add('createdAt')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            // ->add('updatedAt')
            // ->add('createdAt')
            // ->add('isDeleted')
            // ->add('id')
            ->add('name')
            ->add('code')
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('updatedAt')
            ->add('createdAt')
            ->add('isDeleted')
            ->add('id')
            ->add('name')
            ->add('code')
        ;
    }
}
