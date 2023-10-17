<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Article;
use App\Form\Article2Type;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/list.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/list', name: 'article_list', methods: ['GET'])]
    public function list(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/list.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/categorie/{slug}', name: 'app_article_categorie', methods: ['GET'])]
    public function CategorieList(ArticleRepository $ArticleRepository ,Categorie $categorie): Response
    {
        return $this->render('article/list.html.twig', [
            'articles' => $ArticleRepository->findBy(['categorie' => $categorie], []),
        ]);
    }

    #[Route('/article/select', name: 'recherche')]
    public function RechercherArticle(): Response
    {

        $form = $this->createFormBuilder(null, [
            'attr' => ['class' => 'd-flex']
        ])
        ->setAction($this->generateUrl('resultat'))
        ->add('elt', TextType::class, ['label' => false, 
        'attr' => ['Placeholder' => 'Rechercher',
        'class' => 'form-control me-2'
        ]])
        ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'btn btn-outline-success']
        ])
        ->setMethod('GET')
        ->getForm();
        return $this->render('article/recherche.html.twig', [
            'form' =>$form->createview()
        ]);
    }

    #[Route('/result', name: 'resultat', methods: ['GET'])]
    public function result(ArticleRepository $ArticleRepository, Request $request): Response
    {
        $form = ($request->get('form'));
        $elt = $form['elt'];
        $articles = $ArticleRepository->findArticleByCategorie($elt);
        return $this->render('article/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    
    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(Article2Type::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{titre}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    #[IsGranted('EDIT', subject:'article')]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Article2Type::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('article_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/show-api/{id}', name: 'app_article_showapi', methods: ['GET'])]
    public function showApi(Article $article, SerializerInterface $serializer): JsonResponse
    {
        $jsonContent = $serializer->serialize($article, 'json', ['groups' => ['article']]);
        return $this->json($jsonContent);
    }
}
