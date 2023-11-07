<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\CategoriesRepository;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/wish', name:'wish')]
class WishController extends AbstractController
{
    #[Route('/list', name: '_list')]
    public function list(WishRepository $wishRepository): Response
    {
        //$wishes = $wishRepository->findBy([], ['dateCreated' => 'DESC']);
        $wishes = $wishRepository->findPublishedWishesWithCategories();
        return $this->render('wish/index.html.twig', [
            'wishes' => $wishes
        ]);
    }

    #[Route('/detail/{id}', name: '_detail')]
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        //$wish = $wishRepository->findBy(['id' => $id]);
        //$category = $categoriesRepository->findOneBy([]);
        $wish = $wishRepository->find($id);
        return $this->render('wish/detail.html.twig', [
            'wish' => $wish
        ]);

    }

    #[Route('/remove/{id}', name: '_remove')]
    #[IsGranted('ROLE_ADMIN')]
    public function remove(int $id, WishRepository $wishRepository, EntityManagerInterface $entityManager): Response
    {
        $wish = $wishRepository->find($id);
        $entityManager->remove($wish);
        $entityManager->flush();
        $this->addFlash('warning', 'L\'idée à correctement été supprimer.');
        return $this->redirectToRoute('wish_list');
    }

    #[Route('/modify/{id}', name: '_modify')]
    #[IsGranted('ROLE_ADMIN')]
    public function modify(int $id, Request $request, WishRepository $wishRepository, EntityManagerInterface $em): Response
    {
        $wish = $wishRepository->find($id);
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($wish);
            $em->flush();
            $this->addFlash('success', 'L\'idée à été modifier');
            return $this->redirectToRoute('wish_detail', ['id'=>$id]);
        }
        return $this->render('wish/form.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/new', name: '_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wish = new Wish();
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $wish->setIsPublished(true);
            $wish->setDateCreated(new \DateTime());
            $wish->setAuthor($this->getUser());
            $entityManager->persist($wish);
            $entityManager->flush();
            $this->addFlash('success', 'Votre idée à été ajouter à la base de donnée.');
            return $this->redirectToRoute('wish_list');
        }
        return $this->render('wish/form.html.twig', [
            'form' => $form
        ]);
    }
}
