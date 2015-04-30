<?php

namespace EX\PlatformBundle\Controller;

use EX\PlatformBundle\Entity\Advert;
use EX\PlatformBundle\Entity\Category;
use EX\PlatformBundle\Form\AdvertEditType;
use EX\PlatformBundle\Form\AdvertType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\InvalidArgumentException;



class AdvertController extends Controller{
  
  
    public function indexAction($page, Request $request)
  {
    if($page<1){
          throw new InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur:"'.$page.'"');
      }
      $nombreParPage=6;
      
      $session=$request->getSession();
      $session->set('idCategorie', null);
      
         $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $ListAdverts = $em
      ->getRepository('EXPlatformBundle:Advert')
      ->getAdverts($nombreParPage,$page)
    ;
   
    $nbPage=ceil(count($ListAdverts)/$nombreParPage);
    
    if($nbPage ==0){
        $nbPage=1;
    }

     return $this->render('EXPlatformBundle:Advert:index.html.twig', array(
      'adverts'         => $ListAdverts,
      'page'            =>$page,
      'nombrePage'      =>$nbPage
    ));  

    
  }

  public function viewAction(Advert $advert)
  {
 

    return $this->render('EXPlatformBundle:Advert:view.html.twig', array(
      'advert'           =>  $advert
        
    ));  
  }
  

   public function addAction(Request $request)
  {
   
    // Création de l'entité Advert
    $advert = new Advert();
    $user = $this->getUser();
    
    $form = $this->createForm(new AdvertType(), $advert);

    if ($form->handleRequest($request)->isValid()) {
         
          $em=$this->getDoctrine()->getManager();
          $advert->setAuthor($user->getUsername());
          $em->persist($advert);
          $em->flush();
     
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
      return $this->redirect($this->generateUrl('ex_platform_view', array('id' => $advert->getId())));
    }

    return $this->render('EXPlatformBundle:Advert:add.html.twig', 
            array(
                'form' => $form->createView(),
        ));
  }
  

  public function editAction(Advert $advert, Request $request)
  {
    
    $form = $this->createForm(new AdvertEditType(), $advert);
    $user = $this->getUser();
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$advert." n'existe pas.");
    }

    // Même mécanisme que pour l'ajout
    if ($form->handleRequest($request)->isValid()) {
       
            
                $em=$this->getDoctrine()->getManager();
                $advert->setAuthor($user->getUsername());
                $em->persist($advert);
                $em->flush();
            

      return $this->redirect($this->generateUrl('ex_platform_view', array('id' => $advert->getId())));
    }

    return $this->render('EXPlatformBundle:Advert:edit.html.twig', array(

      'advert' => $advert,
      'form' =>$form->createView(),

    ));
  }

  public function deleteAction($id, Request $request)
  {
   $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('EXPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->createFormBuilder()->getForm();

    if ($form->handleRequest($request)->isValid()) {
      $em->remove($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

      return $this->redirect($this->generateUrl('ex_platform_home'));
    }

    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('EXPlatformBundle:Advert:delete.html.twig', array(
      'advert' => $advert,
      'form'   => $form->createView()
    ));
  }
  
   public function menuAction()
  {
    // On fixe en dur une liste ici, bien entendu par la suite
    // on la récupérera depuis la BDD !
    $em = $this->getDoctrine()->getManager();
    
    $listCategory = $em->getRepository('EXPlatformBundle:Category')->findAll();

    return $this->render('EXPlatformBundle:Advert:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
      'listCategory' => $listCategory
    ));
  }
  
  public function editImageAction($advertId)
    {
      $em = $this->getDoctrine()->getManager();

      // On récupère l'annonce
      $advert = $em->getRepository('EXPlatformBundle:Advert')->find($advertId);

      // On modifie l'URL de l'image par exemple
      $advert->getImage()->setUrl('test2.png');

      // On n'a pas besoin de persister l'annonce ni l'image.
      // Rappelez-vous, ces entités sont automatiquement persistées car
      // on les a récupérées depuis Doctrine lui-même

      // On déclenche la modification
      $em->flush();

       return $this->redirect($this->generateUrl('ex_platform_home'));
    }
    /**
     * 
     * @ParamConverter("category", options={"mapping": {"id":"id"}})
     * 
     */
    public function advertByCategoryAction(Category $category, $page, Request $request)
    {
        if($page<1){
          throw new InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur:"'.$page.'"');
      }
      
      $nombreParPage=6;
      $session=$request->getSession();
     
      $session->set('idCategorie', $category->getId());
  
      $em = $this->getDoctrine()->getManager();
      
      // On récupère l'annonce
      $adverts = $em->getRepository('EXPlatformBundle:Advert')->getAdvertByCategory($category->getId());
      $nbAdvert=count($adverts);
    
   $nbPage=ceil(count($adverts)/$nombreParPage);
    
    if($nbPage ==0){
        $nbPage=1;
    }
    
       return $this->render('EXPlatformBundle:Advert:index.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
      'adverts' => $adverts,
      'nbAdvert'=>$nbAdvert,
      'page' =>$page,
      'nombrePage'=>$nbPage
    ));
    }
    /**
     * 
     * @ParamConverter("advert", options={"mapping": {"idAdvert":"id"}})
     * 
     */
    public function unAdvertByCategoryAction(Advert $advert, $idCategory)
    {

    return $this->render('EXPlatformBundle:Advert:view.html.twig', array(
      'advert'           => $advert,
      
      'idCategorie' => $idCategory
    ));  
    }
   
    /**
     * @ParamConverter("json")
     */
    public function ParamConverterAction($json){
        return new Response(print_r($json,true));
    }
}