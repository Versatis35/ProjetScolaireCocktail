<?php

namespace App\Controller;

use App\Entity\Cocktail;
use App\Entity\Fruit;
use App\Form\CocktailType;
use App\Repository\CocktailRepository;
use App\Repository\FruitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CocktailRepository $repo): Response
    {
        $listCocktail = $repo->findAll();
        return $this->render('main/index.html.twig', [
            'cocktails' => $listCocktail,
        ]);
    }

    /**
     * @Route("/ajouter", name="ajouter")
     */
    public function ajouter(Request $request,EntityManagerInterface $em, FruitRepository $repoFruit): Response
    {
        $cock = new Cocktail();
        // relier $wish au formulaire
        $formCocktail = $this->createForm(CocktailType::class,$cock);
        // Hydrater le $wish
        $formCocktail->handleRequest($request);
        if ( $formCocktail->isSubmitted()){
            $fruit = new Fruit();
            $valeurFruit = $request->get("fruits");
            $fruit = $repoFruit->findOneBy(
                [
                    'id' => $valeurFruit
                ]
            );
            $cock->setFruit($fruit);
            $em->persist($cock);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('main/ajouter.html.twig', [
            'formCocktail' => $formCocktail->createView(),
        ]);
    }
}

