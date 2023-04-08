<?php

namespace App\Controller;

use Exception;
use App\Entity\SlideShow;
use App\Services\DBlogger;
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
    private DBLogger $dblog;
    const MODULE = 'AdminControllerSlideShow';

    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher, DBlogger $dblog)
    {
        $this->localeSwitcher = $localeSwitcher;
        $this->dblog = $dblog;
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
        $allslides = $repo->findBy([], [ 'datein' => 'ASC']);
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
        if($id === 0) { // Create ?
            $slideshow = new SlideShow();
            $form = $this->createForm(SlideShowType::class, $slideshow);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $lowername = strtolower($slideshow->getName());
                $slideshow->setName($lowername);
                $entityManager->persist($slideshow);
                $entityManager->flush();
                $this->addFlash('success', $lowername.': '.$translator->trans('admin.manageslides.created'));
                $this->dblog->info($slideshow->getName().' created',
                    'SlideShow CREATION',
                    self::MODULE,
                    $request->getSession()->get('email')
                );
                $slideshow = new SlideShow();
            }
        }
        else{   // Read / Update
            $slideshow = $entityManager->getRepository(SlideShow::class)->findOneBy([ 'id' => $id]);
            $form = $this->createForm(SlideShowType::class, $slideshow);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $lowername = strtolower($slideshow->getName());
                $slideshow->setName($lowername);
                $entityManager->persist($slideshow);
                $entityManager->flush();
                $this->addFlash('success', $lowername.': '.$translator->trans('admin.manageslides.updated'));
                $this->dblog->info($slideshow->getName().' updated',
                    'SlideShow UPDATE',
                    self::MODULE,
                    $request->getSession()->get('email')
                );
            }
        }
        $allshow = $entityManager->getRepository(SlideShow::class)->findBy([], [ 'datein' => 'ASC']);
        return $this->render('admin/slide.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'locale' => $loc,
            'slide' => $slideshow,
            'allslides' => $allshow
        ]);
    }
    // --------------------------------------------------------------------------
    #[Route('/slides/delete/{id}', name: 'bootadmin.slide.delete')]
    public function delete(Request $request,
                    int $id, 
                    EntityManagerInterface $entityManager,
                    TranslatorInterface $translator
                ): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        //
        $repo = $entityManager->getRepository(SlideShow::class);
        $slideshow = $repo->findOnebY(['id' => $id ]);
        $entityManager->remove($slideshow);
        $entityManager->flush();
        $this->addFlash('success', $slideshow->getName().': '.$translator->trans('admin.manageslides.deleted'));
        $this->dblog->info($slideshow->getName().' deleted',
            'SlideShow DELETION',
            self::MODULE,
            $request->getSession()->get('email')
        );

        //
        $slideshow = new SlideShow();
        $allshow = $entityManager->getRepository(SlideShow::class)->findBy([], [ 'datein' => 'ASC']);
        $form = $this->createForm(SlideShowType::class, $slideshow);
        return $this->render('admin/slide.html.twig', [
            'form' => $form->createView(),
            'id' => 0,
            'locale' => $loc,
            'slide' => $slideshow,
            'allslides' => $allshow
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
