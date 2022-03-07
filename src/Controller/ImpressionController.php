<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Impression;
use App\Form\ImpressionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImpressionController extends AbstractController
{
    #[Route('/impression/new/{id}', name: 'new_impression')]
    public function new(EntityManagerInterface $manager, Request $request, Film $film)
    {
        $impression = new Impression();
        $formulaire = $this->createForm(ImpressionType::class, $impression);

        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted()){
            $impression->setUser($this->getUser());
            $impression->setCreatedAt(new \DateTimeImmutable());
            $impression->setFilm($film);
            $manager->persist($impression);
            $manager->flush();
            return $this->redirectToRoute('film', ['id'=>$impression->getFilm()->getId()]);

        }


    }

    #[Route('/impression/delete/{id}', name: 'delete_impression')]
    public function suppr(Impression $impression, EntityManagerInterface $manager){

        $id = $impression->getFilm()->getId();

        if ($this->getUser() == $impression->getUser()){

            $manager->remove($impression);
            $manager->flush();
            return $this->redirectToRoute('film', ['id'=>$id]);
        }
        return $this->redirectToRoute('films');


    }

    #[Route('/impression/update/{id}', name: 'update_impression')]
    public function update(Impression $impression, EntityManagerInterface $manager, Request $request){

        if ($this->getUser() == $impression->getUser()){

            $formulaire = $this->createForm(ImpressionType::class, $impression);
            $formulaire->handleRequest($request);

            if ($formulaire->isSubmitted()){

                $manager->persist($impression);
                $manager->flush();
                return $this->redirectToRoute('film', ['id'=>$impression->getFilm()->getId()]);

            }

            return $this->renderForm('impression/change.html.twig', ['formulaire'=>$formulaire]);

        }

        return $this->redirectToRoute('films');

    }
}
