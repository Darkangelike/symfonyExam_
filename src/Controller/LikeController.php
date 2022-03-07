<?php

namespace App\Controller;

use App\Entity\Impression;
use App\Entity\Like;
use App\Entity\Movie;
use App\Form\ImpressionType;
use App\Repository\ImpressionRepository;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/movie/like/{id}', name: 'movie_like')]
    public function movieLike(Movie $movie, EntityManagerInterface $manager, LikeRepository $repository): Response
    {

        $like = $repository->findOneBy([ "user"=>$this->getUser(), "movie"=> $movie ]);
        $like->setUser($this->getUser());
        $like->setMovie($movie);

        if (!$like) {
        $manager->persist($like);
        $manager->flush();

        }

        return $this->redirectToRoute('movie_show', [
            "id" => $movie->getId(),
        ]);
    }

    #[Route('/impression/like/{id}', name: 'impression_like', priority: '2')]
    public function impressionLike(Impression $impression, EntityManagerInterface $manager, ImpressionRepository $repository):
    Response
    {

        $like = $repository->findOneBy([ "user"=>$this->getUser(), "impression"=> $impression ]);
        $like->setUser($this->getUser());
        $like->setImpression($impression);

        if (!$like) {
            $manager->persist($like);
            $manager->flush();

        }


        return $this->redirectToRoute('movie_show', [
            "id" => $impression->getId(),
        ]);
    }
}
