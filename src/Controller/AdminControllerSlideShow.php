<?php

namespace App\Controller;

use App\Entity\SlideShow;
use App\Form\SlideShowType;
use App\Repository\SlideShowRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/bootadmin')]
class AdminControllerSlideShow extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;
    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher)
    {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    //      S L I D E  S H O W   S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/slides/list', name: 'bootadmin.slideshow.list')]
    public function list(Request $request,
                    EntityManagerInterface $entityManager,
                    TranslatorInterface $translator
                ): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        /** 
         * @var SlideShowRepository $repo 
         * 
        */
        $repo = $entityManager->getRepository(SlideShow::class);
        $allslides = $repo->findAll();
        $slide = new SlideShow();
        $form = $this->createForm(SlideShowType::class, $slide);
        return $this->render('admin/slides.html.twig', [
            'form' => $form->createView(),
            'allslides' => $allslides,
            'locale' => $loc
        ]);
    }
    // --------------------------------------------------------------------------
    #[Route('/slides/edit/{id?0}', name: 'bootadmin.slide.edit')]
    public function edit(Request $request,
                    int $id, 
                    EntityManagerInterface $entityManager,
                    TranslatorInterface $translator
                ): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $slide = new SlideShow();
        $form = $this->createForm(SlideShowType::class, $slide);
        return $this->render('admin/slide.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'locale' => $loc,
            'slide' => $slide
        ]);
    }
    // --------------------------------------------------------------------------
    // P R I V A T E     S E R V I C E S 
    // --------------------------------------------------------------------------
    private function locale(Request $request) {
        $session = $request->getSession();
        if($session->has('bootadmin.lang')) {
            $loc = $session->get('bootadmin.lang');
        }
        else {
            $loc = $this->localeSwitcher->getLocale();
            $session->set('bootadmin.lang', $loc);
        }
        $this->localeSwitcher->setLocale($loc);
        return $loc;
    }
}
