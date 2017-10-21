<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Note;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        
      

        

        //on créé le formulaire
        $unenote = new Note();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class,$unenote);

        //on ajoute les champs
        $formBuilder
        ->add('Nom',       TextType::class)
        ->add('Ajouter',   SubmitType::class);

        //on genere le form
        $form = $formBuilder->getForm();

        //on regarde si le formulaire est posté
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);
      
            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
              // On enregistre notre objet $advert dans la base de données, par exemple
              $em = $this->getDoctrine()->getManager();
              $em->persist($unenote);
              $em->flush();
      
              $request->getSession()->getFlashBag()->add('notice', 'Note bien enregistrée.');

              $noteRepo = $em->getRepository('AppBundle:Note');
      
              $listNotes= $noteRepo->findAll();
      
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
              return $this->render('accueil/index.html.twig', ['nom'=> "Benoit", 'imgurl'=> "https://cms-assets.tutsplus.com/uploads/users/164/posts/25750/image/image3.jpg",'h1color'=>"blue",'noteform' => $form->createView(),'listNote'=>$listNotes]);
            }
        }

        //on va récupérer la liste des notes ici 

        $em = $this->getDoctrine()->getManager();
        $noteRepo = $em->getRepository('AppBundle:Note');

        //on recupere la liste
        $listNotes= $noteRepo->findAll();

        return $this->render('accueil/index.html.twig', ['nom'=> "Note de frais", 'imgurl'=> "https://cms-assets.tutsplus.com/uploads/users/164/posts/25750/image/image3.jpg",'h1color'=>"blue",'noteform' => $form->createView(),'listNote'=>$listNotes]);
    }
    public function addAction(Request $request){

        
        //On se créé une note
        $note = new Note();
        $note->setNom("test");
        
        //on récupere l'entity manager
        $em = $this->getDoctrine()->getManager();

        //on persiste
        $em->persist($note);

        //on flush ce qui est persisté
        $em->flush();

        //on regirige vers l'accueil
        return $this->redirectToRoute('app_home');

    }
}
