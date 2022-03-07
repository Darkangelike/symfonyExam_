<?php

namespace App\Controller;

use App\Entity\Impression;
use App\Form\ImpressionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImpressionController extends AbstractController
{
    /**
     * @Route("/impression/remove/{id}", name="impression_remove")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     */
    public function remove(EntityManagerInterface $manager, Impression $impression){

        $movieId = $impression->getMovie()->getId();
        $manager->remove($impression);
        $manager->flush();

        return $this->redirectToRoute("movie_show", [
            "id" => $movieId,
        ]);
    }


    /**
     *
     * @Route("/impression/edit/{id}", name="impression_edit")
     * @return Response
     *
     *
     */
    public function edit(Impression $impression, EntityManagerInterface $manager, Request $request){

        $form = $this->createForm(ImpressionType::class, $impression);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($impression);
            $manager->flush();

            return $this->redirectToRoute("movie_show", [
                "id" => $impression->getMovie()->getId(),
            ]);
        }

        return $this->renderForm("impression/edit.html.twig", [
            "impression" => $impression,
            "form" => $form,
        ]);
    }
}
