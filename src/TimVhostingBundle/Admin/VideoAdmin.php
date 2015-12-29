<?php

namespace TimVhostingBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use TimConfigBundle\Admin\Base\BaseAdmin;
use TimVhostingBundle\Entity\Video;

class VideoAdmin extends BaseAdmin
{
    protected $baseRouteName = 'video';

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            // ->add('isDeleted')
            ->add('id')
            ->add('name')
            ->add('link')
            ->add('description')
            ->add('meta')
            ->add('isPublic')
            ->add('updatedAt')
            ->add('createdAt')
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
            // ->add('isDeleted')
            ->addIdentifier('id')
            ->add('name')
            // ->add('VideoSuggest.title')
            // ->add('VideoSuggest', 'many_to_one')
            // ->add('VideoSuggest.title', null, array('route' => array( 'name' => 'edit')))
            ->add('videoSuggest')
            // ->add('videoRate')
            ->add('tags')
            ->add('link')
            ->add('description')
            ->add('meta')
            ->add('isPublic')
            ->add('author')
            ->add('updatedAt')
            ->add('createdAt')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            // ->add('updatedAt')
            // ->add('createdAt')
            // ->add('isDeleted')
            // ->add('id')
            ->add('name')
            ->add('tags', null, array('multiple' => true))
            // ->add('videoSuggest')
            ->add('link')
            ->add('youtubeVideoId')
            ->add('description')
            ->add('meta')
            ->add('isPublic')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            // ->add('isDeleted')
            ->add('id')
            ->add('name')
            ->add('link')
            ->add('youtubeVideoId')
            ->add('tags')
            ->add('videoSuggest')
            ->add('videoRate')
            ->add('description')
            ->add('meta')
            ->add('isPublic')
            ->add('updatedAt')
            ->add('createdAt')
        ;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->addSelect('t')
            ->leftJoin($query->getRootAlias().'.tags', 't')
            ->addSelect('vs')
            ->leftJoin($query->getRootAlias().'.videoSuggest', 'vs')
            ->addSelect('u')
            ->leftJoin($query->getRootAlias().'.author', 'u')
        ;

        return $query;
    }

    /**
     * @param Video $object
     * @return mixed|void
     */
    public function preUpdate($object)
    {
        parent::preUpdate($object);

        if (!is_null($object->getYoutubeVideoId())) {
            $this->updateDataFromYoutubeService($object);
        }
    }

    public function prePersist($object)
    {
        parent::prePersist($object);

        if (!is_null($object->getYoutubeVideoId())) {
            $this->updateDataFromYoutubeService($object);
        }
    }

    /**
     * @param ErrorElement $errorElement
     * @param Video $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        parent::validate($errorElement, $object);

        if (is_null($object->getYoutubeVideoId()) && is_null($object->getLink())) {
            $errorElement->with('youtubeVideoId')->addViolation('Please, fill YoutubeVideoId or Link')->end();
            $errorElement->with('link')->addViolation('Please, fill YoutubeVideoId or Link')->end();
        }
    }

    /**
     * @param Video $object
     * @return mixed|void
     */
    private function updateDataFromYoutubeService($object)
    {
        $serviceYoutube = $this->container->get('tim_vhosting.google_api.handler');
        $data = $serviceYoutube->getYoutubeVideoInfo($object->getYoutubeVideoId());

        $object->setDurationVideo($serviceYoutube->getYoutubeVideoDurationFromData($data));

        $statistics = $serviceYoutube->getYoutubeVideoStatisticsFromData($data);
        $object->setViewCount($statistics->getViewCount());
        $object->setLikeCount($statistics->getLikeCount());
        $object->setDislikeCount($statistics->getDislikeCount());
        $object->setFavoriteCount($statistics->getFavoriteCount());
    }
}
