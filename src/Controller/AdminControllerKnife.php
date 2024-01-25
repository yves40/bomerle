<?php

namespace App\Controller;

use Error;

use Exception;
use App\Entity\Images;
use App\Entity\Knifes;
use App\Entity\Category;

use App\Form\KnifesType;
use App\Services\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/knives')]
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
    #[Route('/protected/all', name: 'bootadmin.knives.all')]
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
    #[Route('/protected/delete/{id}', name: 'bootadmin.knives.delete')]
    public function Deleteknife(Request $request,
                                int $id,
                                Uploader $uploader,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        $repo = $entityManager->getRepository(Knifes::class);
        $knife = $repo->find($id);
        // Cleanup the uploaded files from the file system
        $images = $knife->getImages();
        $physicalPath = $this->getParameter('knifeimages_directory');
        foreach($images as $img) {
            $uploader->deleteFile($physicalPath.'/'.$img->getFilename());
        };
        // Cleanup the DB
        $repo->remove($knife, true);
        $this->addFlash('success', $translator->trans('admin.manageknives.deleted'));
        return $this->redirectToRoute('bootadmin.knives.all', array( 'new' => "true"));
    }
    // --------------------------------------------------------------------------
    #[Route('/protected/edit/{id?0}', name: 'bootadmin.knives.edit')]
    public function edit(Request $request,
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
            return $this->redirectToRoute('bootadmin.knives.edit', array( 'id' => $knife->getId()));    // Back to knife management screen
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
    #[Route('/protected/removephoto/{knifeid?0}/{imageid?0}', name: 'bootadmin.knives.removephoto')]
    public function removePhoto(Request $request,
        int $knifeid,
        int $imageid,
        Uploader $uploader,
        EntityManagerInterface $emgr)
    {
        $loc = $this->locale($request);
        $imagepath = $this->getParameter('knifeimages_directory');  // Defined in services.yaml
        $knife = $emgr->getRepository(Knifes::class)->findOneBy([ 'id' => $knifeid]);
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
        $knife->removeImage($image);    // Shoot image from knife object
        $emgr->persist($knife);
        $emgr->flush();
        // Reset the rank column after image deletion
        $imglist = $emgr->getRepository(Images::class)->findKnifeImagesByRank($knife);
        $rank = 0;
        foreach($imglist as $key => $oneimage) {
            $oneimage->setRank(++$rank);
            $emgr->persist($oneimage);
        }
        $emgr->flush();

        return $this->json([
            'message' => 'bootadmin.knives.removephoto called',
            'knifeid' => $knifeid,
            'imageid' => $imageid,
            'reordered' => $rank
        ], 200);
    }
    // --------------------------------------------------------------------------
    #[Route('/protected/swapphotos', name: 'bootadmin.knives.swapphotos')]
    public function swapPhotos(Request $request,
        EntityManagerInterface $emgr)
    {
        $trace = [];
        try {
            $data = file_get_contents("php://input");
            $payload = json_decode($data, true);
            $imagelist = $payload['imagedata'];

            $loc = $this->locale($request);
            $repo = $emgr->getRepository(Images::class);
            $rankindex = 0;

            foreach($imagelist as $key => $value) {
                array_push($trace, $value['imageid']);
                $img = $repo->findOneBy([ 'id' => $value['imageid']]);
                $img->setRank(++$rankindex);
                $emgr->persist($img);
            }
            $emgr->flush();
            return $this->json([
                'message' => 'bootadmin.knives.photoswap OK',
                'trace' => $trace,
                'imagelist' => $imagelist
            ], 200);    
        }
        catch(Exception $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'data' => $data, 
                'payload' => $payload,
                'imagelist' => $imagelist
            ], 400);
        }
    }
    // --------------------------------------------------------------------------
    #[Route('/public/getactivecategories', name: 'bootadmin.knives.getactivecategories')]
    public function getActiveCategories(Request $request, EntityManagerInterface $em) {
        try {   
             /**
              *   @var CategoryRepository $repocat  
             */
            $repocat = $em->getRepository(Category::class);
            $distinctcategories = $repocat->findDistinctActiveCategories();
            return $this->json([
                'message' => 'bootadmin.knives.getactivecategories OK',
                'activecategories' => $distinctcategories,
                // 'related' => $linkcat,
                'categoriescount' => count($distinctcategories)
            ], 200);        
    }
        catch(Exception $e) {
            return $this->json([
                'message' => 'bootadmin.knives.getactivecategories KO',
                'error' => $e
            ], 500);        
        }
    }
    // --------------------------------------------------------------------------
    #[Route('/public/getpublished', name: 'bootadmin.knives.getpublished')]
    public function getPublished(Request $request, EntityManagerInterface $em) {
        try {
            /** @var KnifesRepository $repo */
            $repo = $em->getRepository(Knifes::class);
            // Cannot directly send back $published as the knife object 
            // contains an object attribute for Category
            // So rebuild a dedicated array for the client
            $published = $repo->findPublished();
            $categories = [];
            $knives = [];
            /** @var Knifes $one */
            foreach($published as $key => $one) {
                $knives[$key]['id'] = $one->getId();
                $knives[$key]['knifename'] = $one->getName();
                $knives[$key]['catname'] = $one->getCategory()->getName();
                $knives[$key]['catid'] = $one->getCategory()->getId();
                $categories[$key]['catname'] = $one->getCategory()->getName();
                $categories[$key]['catfullname'] = $one->getCategory()->getFullname();
                $categories[$key]['catid'] = $one->getCategory()->getId();
                $categories[$key]['catdesc'] = $one->getCategory()->getDescription();
                $categories[$key]['catrank'] = $one->getCategory()->getRank();
            }
            // $dedup = array_unique($categories);
            return $this->json([
                'message' => 'bootadmin.knives.getpublished OK',
                'knives' => $knives,
                'knivescount' => count($published),
                'categories' =>  $categories,
                'categoriescount' => count($categories)
            ], 200);        
    }
        catch(Exception $e) {
            return $this->json([
                'message' => 'bootadmin.knives.getpublished KO',
                'error' => $e
            ], 500);        
        }
    }
    // --------------------------------------------------------------------------
    #[Route('/public/categoryimages', name: 'bootadmin.knives.categoryimages')]
    public function getCategoryImages(Request $request, EntityManagerInterface $em) {
        $data = file_get_contents("php://input");
        $payload = json_decode($data, true);
        $categoryid = $payload['catid'];
        $single = $payload['single'];

        /*
            select filename from images where knifes_id 
                in ( SELECT id FROM `knifes` WHERE category_id = 19) LIMIT 1;
            select distinct c.name, c.image, c.rank 
                from category c, knifes k where c.id = k.category_id order by c.rank;
        */
        try {
            /** 
             * @var KnifeRepository $repoknife 
             * @var CategoryRepository $repocat  
             * */
            $repoknife = $em->getRepository(Knifes::class);
            $repocat = $em->getRepository(Category::class);
            $filenames = [];
            $knivesid = [];
            $relatedcategories = [];
            if($single) {
                $oneknife = $repoknife->findBy(['category' => $categoryid, 'published' => true  ], [ ], 1);
                $images = $oneknife[0]->getImages();
                foreach($images as $img) {
                    array_push($filenames, $img->getFilename());
                }
                array_push($knivesid, $oneknife[0]->getID());
            }
            else {
                $knives = $repoknife->findBy(['category' => $categoryid, 'published' => true  ]);
                foreach($knives as $knife) {
                    $images = $knife->getImages();
                    foreach($images as $img) {
                        array_push($filenames, $img->getFilename());
                        array_push($knivesid, $knife->getID());
                    }
                }
            }
            // Get related categories ( 0 to n )
            $cats = $repocat->findBy(['id' => $categoryid], []);
            foreach($cats as $onecat) {
                $rel = $onecat->getRelatedcategories();
                foreach($rel as $one) {
                    array_push($relatedcategories, $one->getId());
                }
            }
            $thecategory = $repocat->findOneBy(['id' => $categoryid ]);
            return $this->json([
                'message' => 'bootadmin.knives.categoryimages OK',
                'catid' => $categoryid,
                'catimage' => $thecategory->getImage(),
                'relatedcategories' => $relatedcategories,
                'single' => $single,
                'filenames' => $filenames,
                'knivesid' => $knivesid,
            ], 200);        
    }
        catch(Exception $e) {
            return $this->json([
                'message' => 'bootadmin.knives.categoryimages KO',
                'error' => $e
            ], 500);        
        }
    }
    // --------------------------------------------------------------------------
    #[Route('/public/getimages/{knifeid?0}', name: 'bootadmin.knives.getimages')]
    public function getImages(Request $request, 
                            EntityManagerInterface $em,
                            ) 
    {
        /** @var Knifes $knife */
        /** @var Collection $images */
        $data = file_get_contents("php://input");
        $payload = json_decode($data, true);
        $knifeid = $payload['knifeid'];
        try {
            $knife = $em->getRepository(Knifes::class)->findOneBy([ 'id' => $knifeid]);
            $images = $knife->getImages();
            $filenames = [];
            foreach($images as $img) {
                array_push($filenames, $img->getFilename());
            }
            // $images = $em->getRepository(Images::class)->findKnifeImagesByRank($knife);
            return $this->json([
                'message' => 'bootadmin.knives.getimages KO for knife ID : '. $knifeid,
                'knifeId' => $knife->getId(),
                'knifeName' => $knife->getName(),
                'knifedesc' => $knife->getDescription(),
                "imagecount" => count($images),
                "images" => $filenames
            ], 200);        
        }
        catch(Exception $e) {
            return $this->json([
                'message' => 'bootadmin.knives.getimages KO for knife ID : '. $knifeid,
                'error' => $e
            ], 400);        
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
