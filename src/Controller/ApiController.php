<?php

namespace App\Controller;

use App\Entity\Cocktail;
use App\Repository\CocktailRepository;
use App\Repository\CouleurRepository;
use App\Repository\FruitRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{

    /**
     * @Route("/api/cocktail/", name="api_liste_cocktail" ,methods={"GET"})
     */
    public function index(CocktailRepository $repo, SerializerInterface  $serializer): Response
    {
        return $this->json($repo->findAll());
        /*
        $list = $repo->findAll();
        $json = $serializer->serialize($list, 'json', ['groups'=>'post:read']);
        $response = new Response($json, 200 , [
            "Content-Type"=>"application/json"
        ]);
        return $response;
        */
    }

    /**
     * @Route("/api/cocktail/", name="api_add_cocktail" ,methods={"POST"})
     */
    public function ajouter(Request $request,EntityManagerInterface $em): Response
    {
        $json =$request->getContent();
        $obj = json_decode($json);

        $cock = new Cocktail();
        $cock->setNom($obj->nom);
        $cock->setFruit($obj->fruit);
        $em->persist($cock);
        $em->flush();
        return $this->json($cock);
    }

    /**
     * @Route("/api/cocktail/{id}", name="api_enlever_cocktail" ,methods={"DELETE"})
     */
    public function delete(Cocktail $cock,EntityManagerInterface $em): Response
    {
        $em->remove($cock);
        $em->flush();
        return $this->json($cock);
    }

    /**
     * @Route("/api/couleur/", name="api_liste_couleur" ,methods={"GET"})
     */
    public function couleurs(CouleurRepository $repoC): Response
    {
        return $this->json($repoC->findAll());
    }

    /**
     * @Route("/api/fruits/", name="api_liste_fruit" ,methods={"GET"})
     */
    public function fruits(FruitRepository  $repoC): Response
    {
        return $this->json($repoC->findAll());
    }

}
