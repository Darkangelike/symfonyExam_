<?php

namespace App\Controller;

use App\Entity\Impression;
use App\Entity\Movie;
use App\Form\ImpressionType;
use App\Form\MovieType;
use App\Repository\ImpressionRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(MovieRepository $repo): Response
    {

        $movies = $repo->findAll();

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }


    /**
     * @Route("/movie/{id}", name="movie_show")
     * @return Response
     *
     */
    public function show(Movie $movie, EntityManagerInterface $manager, Request $request){


        $impression = new Impression();
        $form = $this->createForm(ImpressionType::class, $impression);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $impression->setMovie($movie);
            $impression->setUser($this->getUser());
            $impression->setCreatedAt(new \DateTime());
            $manager->persist($impression);
            $manager->flush();

            return $this->redirectToRoute("movie_show", [
                "id" => $movie->getId()
            ]);
        }

        return $this->renderForm("movie/show.html.twig", [
            "movie" => $movie,
            "form" => $form
        ]);
    }

    /**
     * @Route("/movie/add", name="movie_add", priority="2")
     * @return Response
     */
    public function addMovie(Request $request, EntityManagerInterface $manager){

        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $movie->setUser($this->getUser());
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute("index", [

            ]);
        }

        return $this->renderForm('movie/add.html.twig', [
            "form" => $form
        ]);
    }


    /**
     *
     * @Route("/movie/remove/{id}", name="movie_remove")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     */
    public function removeMovie(MovieRepository $repo, Movie $movie, EntityManagerInterface $manager){

        $manager->remove($movie);
        $manager->flush();

        return $this->redirectToRoute("index");
    }


    /**
     *
     * @Route("/movie/edit/{id}", name="movie_edit", priority="2")
     * @return Response
     *
     */
    public function editMovie(Movie $movie, EntityManagerInterface $manager, Request $request){

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute("index", [

            ]);
        }

        return $this->renderForm("movie/edit.html.twig", [
            "form" => $form,
            "movie" => $movie
        ]);
    }
}
