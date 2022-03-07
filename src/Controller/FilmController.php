<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Impression;
use App\Form\FilmType;
use App\Form\ImpressionType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    #[Route('/', name: 'films')]
    public function index(FilmRepository $repository): Response
    {

        return $this->render('film/index.html.twig', [
            'films' => $repository->findAll()
        ]);
    }

    #[Route('/film/{id}', name: 'film')]
    public function show(Film $film){

        $impression = new Impression();
        $formulaire = $this->createForm(ImpressionType::class, $impression);

        return $this->renderForm('film/show.html.twig', ['film'=>$film, 'formulaire'=>$formulaire]);
    }

    #[Route('/film/new', name: 'new_film', priority:2)]
    public function new(Request $request, EntityManagerInterface $manager){

        $film = new Film();
        $formulaire = $this->createForm(FilmType::class, $film);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted()){

            $film->setUser($this->getUser());
            $manager->persist($film);
            $manager->flush();
            return $this->redirectToRoute("films");

        }

        return $this->renderForm('film/new.html.twig', ['formulaire'=>$formulaire]);
    }

    #[Route('/film/delete/{id}', name: 'delete_film')]
    public function suppr(Film $film, EntityManagerInterface $manager){

        if ($this->getUser() == $film->getUser()){

            $manager->remove($film);
            $manager->flush();

        }

        return $this->redirectToRoute('films');

    }

    #[Route('/film/update/{id}', name: 'update_film')]
    public function update(Film $film, Request $request, EntityManagerInterface $manager){

        if ($this->getUser() == $film->getUser()){

            $formulaire = $this->createForm(FilmType::class, $film);
            $formulaire->handleRequest($request);

            if ($formulaire->isSubmitted()){

                $manager->persist($film);
                $manager->flush();
                return $this->redirectToRoute('film', ['id'=>$film->getId()]);

            }

            return $this->renderForm('film/change.html.twig', ['formulaire'=>$formulaire]);
        }

        return $this->redirectToRoute('films');

    }
}
