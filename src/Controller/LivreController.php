<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Repository\LivreRepository;
use App\Form\LivreType;
use App\Repository\CategorieRepository;

#[Route('/admin')]
class LivreController extends AbstractController
{

    #[Route('/livre', name: 'livre')]
    public function index(LivreRepository $lr): Response
    {
        return $this->render("livre/index.html.twig", [
             "livres"=>$lr->findAll(),
             "livres_empruntes"=>$lr->livresEmpruntes()
        ]);
    }

    #[Route('/mes-livres', name: 'livre_mes_livres')]
    public function meslivres(): Response
    {
        $mesLivres = [
            [ "titre" => "Dune", "auteur" => "Franck Herbert" ],
            [ "titre" => "1984", "auteur" => "George Orwell" ],
            [ "titre" => "Le Seigneur des Anneaux", "auteur" => "J.R.R. Tolkien" ]
        ];
        // echo $mesLivres[1]["auteur"];

        return $this->render("livre/meslivres.html.twig", [ "livres" => $mesLivres ]);
    }

    #[Route('/livre/ajouter', name: 'livre_ajouter')]
    public function ajouter(Request $request, EntityManager $em, CategorieRepository $cr): Response
    {
        if($request->isMethod("POST")){
            $titre = $request->request->get("titre");// methode permet de recuperer les valeurs des imput dans le formulaire
            $auteur = $request->request->get("auteur");
            $categorie_id = $request->request->get("categorie");
            if($titre && $auteur){// si titre et auteur ne sont pas vide
                $nouveauLivre = new Livre;
                $nouveauLivre->setTitre($titre);
                $nouveauLivre->setAuteur($auteur);
                $nouveauLivre->setCategorie($cr->find($categorie_id));
                $em->persist($nouveauLivre);

                $em->flush();
                return $this->redirectToRoute("livre");
            }

        }
        //dump($_POST);
        return $this->render("livre/formulaire.html.twig", ["categories"=>$cr->findAll()]);
    }

    #[Route('/livre/modifier/{id}', name: 'livre_modifier')]
    public function modifier(EntityManager $em, Request $request, LivreRepository $lr, $id): Response
    {
        $livre = $lr->find($id);// retrouve dans le bdd
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            if ($fichier=$form->get("couverture")->getData()){
                $nomFichier=pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $nomFichier=str_replace("","_",$nomFichier);
                $nomFichier.=uniqid()."." . $fichier->guessExtension();
                $fichier->move($this->getParameter("dossier_images"), $nomFichier);
                $livre->setCouverture($nomFichier);
            }
            $em->flush();
            return $this->redirectToRoute("livre");
        }

        return $this->render("livre/form.html.twig", [ "formLivre"=>$form->createView()]);
    }

    #[Route('/livre/supprimer/{id}', name: 'livre_supprimer')]
    public function supprimer(Request $request, EntityManager $em, Livre $livre): Response
    {
        //dd($livre);
        
        if($request->isMethod("POST")){
            $em->remove($livre);
            $em->flush();
            return $this->redirectToRoute("livre");
        }
        return $this->render("livre/supprimer.html.twig", ["livre"=>$livre]);
    }
    #[Route('/livre/fiche/{id}', name: 'livre_fiche')]
    public function fiche( Livre $livre ): Response
    {
        return $this->render("livre/fiche.html.twig", compact("livre"));
    }
    #[Route('/livre/nouveau', name: 'livre_nouveau')]
    public function nouveau( EntityManager $em, Request $request): Response{

        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            if ($fichier=$form->get("couverture")->getData()){
                $nomFichier=pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $nomFichier=str_replace("","_",$nomFichier);
                $nomFichier.=uniqid()."." . $fichier->guessExtension();
                $fichier->move($this->getParameter("dossier_images"), $nomFichier);
                $livre->setCouverture($nomFichier);
            }
            $em->persist($livre);
            $em->flush();
            $this->addFlash("success", "le nouveau livre à été enregistré");
            return $this->redirectToRoute("livre");
        }

        return $this->render("livre/form.html.twig", [ "formLivre"=>$form->createView()]);

    }
}



//Créer l'entité Categorie
//Mettre à jour la base de données
//Faire les routes pour ajouter, modifier, supprimer une catégorie
//Afficher la liste des catégories

/*
EXO : 1. Sur les vignettes des livres, le lien 'emprunter' ne doit apparaitre que si le livre est disponible

	2. on ne doit pas pouvoir emprunter un livre qui n'est pas disponible (même si le lien est caché, l'utilisateur
    	peut mettre l'adresse dans la barre)
        
        
        
    3. le résultat de la recherche affiche maintenant une erreur. Résoudre cette erreur
    
    4. Dans la fiche Catégorie (route categorie_show), afficher tous les livres qui correspondent à la catégorie.
    	Il doit y avoir un lien vers la fiche livre (livre_fiche) pour chaque livre 
        
    5. Afficher des messages de succès pour toutes les routes CRUD
*/