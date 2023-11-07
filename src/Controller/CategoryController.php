<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoryType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/category', name: 'category')]
class CategoryController extends AbstractController
{

    #[Route('/list', name:'_list')]
    public function list(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'Les catégories',
            'categories' => $categories
    ]);
    }
    #[Route('/new', name: '_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Votre catégorie à bien été ajouter.');
            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/form.html.twig', [
            'controller_name' => 'CategoryController',
            'form' => $form
        ]);
    }
}
