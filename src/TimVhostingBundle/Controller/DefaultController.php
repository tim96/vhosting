<?php declare(strict_types=1);

namespace App\TimVhostingBundle\Controller;

use App\TimVhostingBundle\Entity\VideoRepository;
use App\TimVhostingBundle\Handler\FeedbackHandler;
use App\TimVhostingBundle\Handler\TagsHandler;
use App\TimVhostingBundle\Handler\VideoSuggestHandler;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\TimVhostingBundle\Entity\Feedback;
use App\TimVhostingBundle\Entity\VideoSuggest;
use App\TimVhostingBundle\Form\FeedbackType;
use App\TimVhostingBundle\Form\VideoSuggestType;

class DefaultController extends AbstractController
{
    /** @var PaginatorInterface */
    private $paginator;

    /** @var TagsHandler */
    private $tagsHandler;

    /** @var VideoRepository */
    private $videoRepository;

    /** @var VideoSuggestHandler */
    private $videoSuggestHandler;

    /** @var FeedbackHandler */
    private $feedbackHandler;

    public function __construct(
        PaginatorInterface $paginator,
        TagsHandler $tagsHandler,
        VideoRepository $videoRepository,
        VideoSuggestHandler $videoSuggestHandler,
        FeedbackHandler $feedbackHandler
    ) {
        $this->paginator = $paginator;
        $this->tagsHandler = $tagsHandler;
        $this->videoRepository = $videoRepository;
        $this->videoSuggestHandler = $videoSuggestHandler;
        $this->feedbackHandler = $feedbackHandler;
    }

    /**
     * @Route("/{page}/{tag}", requirements={"page" = "\d+"}, name="Home", defaults={"page" = 1, "tag" = null})
     *
     * @param Request $request
     * @param int $page
     * @param null $tag
     *
     * @return Response
     */
    public function frontendAction(Request $request, $page = 1, $tag = null): Response
    {
        $serach = $request->query->get('search');

        // todo: Testing new layout
        // $maxVideoOnPage = 10;
        $maxVideoOnPage = 9;

        if (null === $tag) {
            $tags = $this->tagsHandler->getList(array('isDeleted' => false));
        } else {
            $tags = $this->tagsHandler->getList(array('isDeleted' => false, 'name' => $tag));
        }

        $carousel = $this->videoRepository->getTopVideos($maxVideos = 4)->getQuery()->getResult();

        $videos = array();
        // $videos = $this->videoRepository->getList(array('isPublic' => true, 'isDeleted' => false));
        $query = $this->videoRepository->getTagsQuery($tag);
        $query = $this->videoRepository->getSearch($serach, $query)->getQuery();

        $pagination = $this->paginator->paginate(
            $query,
            $page /*page number*/,
            $maxVideoOnPage /*limit per page*/
        );

        // todo: Testing new layout
        // return $this->render('TimVhostingBundle:Default:frontend.html.twig',
        //     array('tags' => $tags, 'videos' => $videos, 'pagination' => $pagination, 'carousel' => $carousel));
        return $this->render('TimVhostingBundle:DefaultBootstrapV4:frontend.html.twig',
            ['tags' => $tags, 'videos' => $videos, 'pagination' => $pagination]);
    }

    /**
     * @Route("/about", name="About")
     */
    public function aboutAction(): Response
    {
        return $this->render('TimVhostingBundle:DefaultBootstrapV4:about.html.twig', array());
    }

    /**
     * @Route("/contribute", name="Contribute")
     *
     * @param Request $request
     * @return Response
     */
    public function contributeAction(Request $request): Response
    {
        $videoSuggest = new VideoSuggest();
        $form = $this->createForm(VideoSuggestType::class, $videoSuggest);
        $form->add('save', SubmitType::class, array('label' => 'save.button.label'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $record = $this->videoSuggestHandler->create($form->getData());

                $this->addFlash('notice', 'Thank you, for your contribution!');
            } catch(\Exception $ex) {
                $this->addFlash('error', 'Sorry, something wrong');
            }

            return $this->redirectToRoute('Contribute');
        }

        // todo: Testing new layout
        // return $this->render('TimVhostingBundle:Default:contribute.html.twig', [
        return $this->render('TimVhostingBundle:DefaultBootstrapV4:contribute.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contact", name="Contact")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function contactAction(Request $request)
    {
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->add('save', SubmitType::class, array('label' => 'submit.button.label'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $record = $this->feedbackHandler->create($form->getData());

                $this->addFlash('notice', 'Thank you, for your feedback!');
            }
            catch(\Exception $ex)
            {
                $this->addFlash('error', 'Sorry, something wrong');
            }

            return $this->redirectToRoute('Contact');
        }

        // todo: Testing new layout
        // return $this->render('TimVhostingBundle:Default:contact.html.twig', [
        return $this->render('TimVhostingBundle:DefaultBootstrapV4:contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/changeLanguage/{name}", name="ChangeLanguage")
     */
    public function changeLanguageAction($name, Request $request)
    {
        $locale = substr($name, 0, 2);

        $session = $request->getSession();

        if ($session) {
            $session->set('_locale', $locale);
        }

        $request->setLocale($locale);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}
