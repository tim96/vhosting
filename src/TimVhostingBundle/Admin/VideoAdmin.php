<?php declare(strict_types = 1);

namespace App\TimVhostingBundle\Admin;

use App\TimVhostingBundle\Handler\GoogleApiHandler;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use App\TimConfigBundle\Admin\Base\BaseAdmin;
use App\TimVhostingBundle\Entity\Video;
use Sonata\Form\Validator\ErrorElement;

class VideoAdmin extends BaseAdmin
{
    protected $baseRouteName = 'video';

    /** @var GoogleApiHandler */
    private $apiHandler;

    /**
     * @Required
     *
     * @param GoogleApiHandler $apiHandler
     */
    public function setApiHandler(GoogleApiHandler $apiHandler): void
    {
        $this->apiHandler = $apiHandler;
    }

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
            ->add('likeCount')
            ->add('viewCount')
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
                    'publish' => array(
                        'template' => 'TimVhostingBundle:Video:list__action_publish.html.twig'
                    ),
                    'unpublish' => array(
                        'template' => 'TimVhostingBundle:Video:list__action_unpublish.html.twig'
                    ),
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
            ->add('likeCount')
            ->add('viewCount')
            ->add('dislikeCount')
            ->add('favoriteCount')
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
            ->add('language')
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

    public function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);

        $collection->add('publish', $this->getRouterIdParameter().'/publish');
        $collection->add('unpublish', $this->getRouterIdParameter().'/unpublish');
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
        $data = $this->apiHandler->getYoutubeVideoInfo($object->getYoutubeVideoId());

        $object->setDurationVideo($this->apiHandler->getYoutubeVideoDurationFromData($data));

        $statistics = $this->apiHandler->getYoutubeVideoStatisticsFromData($data);
        $object->setViewCount($statistics->getViewCount());
        $object->setLikeCount($statistics->getLikeCount());
        $object->setDislikeCount($statistics->getDislikeCount());
        $object->setFavoriteCount($statistics->getFavoriteCount());
    }

    protected function configureBatchActions($actions)
    {
        $actions = parent::configureBatchActions($actions);

        if ($this->hasRoute('edit') && $this->hasAccess('edit')) {
            $actions['publish'] = [
                'label' => 'action_publish',
                'ask_confirmation' => true,
            ];
            $actions['unpublish'] = [
                'label' => 'action_unpublish',
                'ask_confirmation' => true,
            ];
        }

        return $actions;
    }
}
