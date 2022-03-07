<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Impression;
use App\Entity\Like;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/film/{id}', name: 'like_film')]
    public function likeFilm(LikeRepository $repository, Film $film, EntityManagerInterface $manager): Response
    {
        $like = $repository->findOneBy([
            "film"=>$film,
            "user"=>$this->getUser()
        ]);


        if (!$like){
            $like = new Like();

            $like->setUser($this->getUser());
            $like->setFilm($film);
            $manager->persist($like);
            $isLiked = false;
        }else{
            $manager->remove($like);
            $isLiked = true;
        }


        $manager->flush();
        $count = $repository->count(["film"=>$film]);
        return $this->redirectToRoute('films');

    }


    #[Route('/like/impression/{id}', name: 'like_impression')]
    public function likeImpression(LikeRepository $repository, Impression $impression, EntityManagerInterface $manager): Response
    {
        $like = $repository->findOneBy([
            "impression"=>$impression,
            "user"=>$this->getUser()
        ]);


        if (!$like){
            $like = new Like();

            $like->setUser($this->getUser());
            $like->setImpression($impression);
            $manager->persist($like);
            $isLiked = false;
        }else{
            $manager->remove($like);
            $isLiked = true;
        }


        $manager->flush();
        $count = $repository->count(["impression"=>$impression]);
        return $this->redirectToRoute('film', ['id'=>$impression->getFilm()->getId()]);

    }

}
