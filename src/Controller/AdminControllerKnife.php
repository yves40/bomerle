<?php

namespace App\Controller;

use Error;

use Exception;
use App\Entity\Images;
use App\Entity\Knifes;
use App\Form\KnifesType;

use App\Services\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/bootadmin')]
class AdminControllerKnife extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;
    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher)
    {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    //      K N I F E S   S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/knives/all', name: 'bootadmin.knives.all')]
    public function home(Request $request,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        $loc = $this->locale($request);
        $knife = new Knifes();
        $repo = $entityManager->getRepository(Knifes::class);
        $allknives = $repo->findBy([], ['name' => 'asc']);

        $form = $this->createForm(KnifesType::class, $knife);
        return $this->render('admin/knives.html.twig', [
            "locale" =>  $loc,
            "new" => true,
            "allknives" => $allknives,
            "knifename" => '',
            "form" => $form->createView()
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/knives/delete/{id}', name: 'bootadmin.knives.delete')]
    public function DeleteMetal(Request $request,
                                int $id,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        $repo = $entityManager->getRepository(Knifes::class);
        $knife = $repo->find($id);
        $repo->remove($knife, true);
        $this->addFlash('success', $translator->trans('admin.manageknives.deleted'));
        return $this->redirectToRoute('bootadmin.knives.all', array( 'new' => "true"));
    }
    // --------------------------------------------------------------------------
    #[Route('/knives/edit/{id?0}', name: 'bootadmin.knives.edit')]
    public function update(Request $request,
                        int $id,    // If 0 : insert mode
                        EntityManagerInterface $entityManager,
                        Uploader $uploader,
                        TranslatorInterface $translator): Response
    {
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Knifes::class);
        $knife = new Knifes();
        $rank = 0;          // Used to order images in the knife photo catalog
        if($id !== 0 ) {    // Update or new ? 
            $knife = $repo->find($id);
            $img = $entityManager->getRepository(Images::class);
            $rank = $img->getMaxRankForKnifeImage($knife);
        }
        $form = $this->createForm(KnifesType::class, $knife);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){   // Form submitted ? 
            // Take care of uploaded images
            $uploadedFiles = $form->get('images')->getData();
            $physicalPath = $this->getParameter('knifeimages_directory');
            for($i=0; $i < count($uploadedFiles); $i++){
                $image = new Images();
                $newFileName = $uploader->uploadFile($uploadedFiles[$i], $physicalPath);
                $image->setFilename($newFileName);
                $image->setRank(++$rank);
                if($i == 0){
                    $image->setMainpicture(true);
                }else{
                    $image->setMainpicture(false);
                }
                $knife->addImage($image);
            }
            // Now can persist the knife object
            $entityManager->persist($knife);
            $entityManager->flush();
            if($id === 0)  {    // Insert ? 
                $this->addFlash('success', $translator->trans('admin.manageknives.created'));
            }
            else {
                $this->addFlash('success', $translator->trans('admin.manageknives.updated'));
            }
            // return $this->redirectToRoute('bootadmin.knives.all', array( 'new' => "true"));
        }
        return $this->render('admin/knife.html.twig', [
            "locale" =>  $loc,
            "id" => $id,
            "knife" => $knife,
            "form" => $form->createView()
            ]
        );               
    }
    // --------------------------------------------------------------------------
    // J S O N    S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/knives/removephoto/{knifeid?0}/{imageid?0}', name: 'bootadmin.knives.removephoto')]
    public function removePhoto(Request $request,
        int $knifeid,
        int $imageid,
        Uploader $uploader,
        EntityManagerInterface $emgr)
    {
        $loc = $this->locale($request);
        $imagepath = $this->getParameter('knifeimages_directory');  // Defined in services.yaml
        $image = $emgr->getRepository(Images::class)->findOneBy([ 'id' => $imageid]);
        if(empty($image)) {
            return $this->json([
                'message' => "Image with ID :" . $imageid . "non trouvée !!" 
            ], 400);
        }
        else {
            $emgr->getRepository(Images::class)->remove($image);
            $uploader->deleteFile($imagepath . '/'. $image->getFilename());
        }
        $knife = $emgr->getRepository(Knifes::class)->findOneBy([ 'id' => $knifeid]);
        $knife->removeImage($image);    // Shoot image from knife object
        $emgr->persist($knife);
        $emgr->flush();
        return $this->json([
            'message' => 'bootadmin.knives.removephoto called',
            'knifeid' => $knifeid,
            'imageid' => $imageid
        ], 200);
    }
    // --------------------------------------------------------------------------
    #[Route('/knives/swapphotos/{imagelist?[]}', name: 'bootadmin.knives.swapphotos')]
    public function swapPhotos(Request $request,
        Array $imagelist,
        EntityManagerInterface $emgr)
    {
        dd($imagelist);
        $loc = $this->locale($request);
        // $knife = $emgr->getRepository(Knifes::class)->findOneBy([ 'id' => $knifeid]);
        $repo = $emgr->getRepository(Images::class);
        $rankindex = 0;
        try {
            foreach($imagelist as $key => $value) {
                $img = $repo->findOneBy([ 'id' => $value->imageid]);
                $img->setRank(++$rankindex);
                var_dump($img);
                $emgr->persist($repo);
            }
            $emgr->flush();
            return $this->json([
                'message' => 'bootadmin.knives.photoswap OK',
                'imagelist' => $imagelist
            ], 200);
    
        }
        catch(Error $e) {
            return $this->json([
                'message' => "bootadmin.knives.photoswap ERROR: " + $e->getMessage(),
            ], 500);
        }
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
