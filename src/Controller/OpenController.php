<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Article;
use Pagerfanta\Pagerfanta;
use App\Repository\TagRepository;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

class OpenController extends AbstractController
{
    #[Route('/articles', name: 'app_article')]
    public function index(
        ArticleRepository $articleRepository,
        Request $request,
        TagRepository $tagRepository,
        ManagerRegistry $doctrine
    ): Response
    {
        $tags = $tagRepository->findAll();

        $queryBuilder = $articleRepository->findByNewestQueryBuilder(
            $request->query->get('article')
        );

        $pagerfanta = new Pagerfanta(new QueryAdapter($queryBuilder));
        $pagerfanta->setMaxPerPage(12);
        $pagerfanta->setCurrentPage($request->query->get('page', 1));

        if ($request->query->get('preview')) {
            return $this->render('article/_searchPreview.html.twig', [
                'pager' => $pagerfanta,
            ]);
        }

        return $this->render('article/index.html.twig', [
            'controller_name' => 'Article',
            'pager' => $pagerfanta,
            'tags' => $tags
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show(
        Article $article,
        ArticleRepository $articleRepository,
        TagRepository $tagRepository,
        Request $request,
        ManagerRegistry $doctrine
        ): Response
    {
        $tags = $tagRepository->findAll();
        $articles = $articleRepository->findAll();
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'articles' => $articles,
            'tags' => $tags
        ]);
    }


    #[Route('/export/pdf/article/{id}', name: 'article_export_pdf')]
    public function exportArticlePdf(
        int $id,
        ArticleRepository $articleRepository,
        Article $article,
        Pdf $knpSnappyPdf
        ): PdfResponse
    {

        $html = $this->renderView('article/pdf.html.twig', [
            'article' => $article,
        ]);

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html, [
                'lowquality' => false,
                'images' => true,
                'disable-smart-shrinking' => true,
                'dpi' => 300,
                'margin-top' => 10,
                'margin-bottom' => 10,
                'orientation' => 'Portrait',
                'page-size' => 'A4',
                'header-spacing' => 0,
                'footer-spacing' => 0,
                'footer-font-size' => 8, 
                'footer-left' => '[page] / [toPage]',
                'footer-right' => 'Exprted on [date]'
            ]),
            'article_' . $article->getTitle() . '.pdf',
            'application/pdf',
            'inline'
        );
    }



    #[Route('/article-tag/{slugtag}', name: 'article_tag')]
        public function tag(
            ArticleRepository $articleRepository,
            Request $request,
            TagRepository $tagRepository,
            string $slugtag = null): Response
        {
            $queryBuilder = $articleRepository->findByNewestQueryBuilder(
                $request->query->get('article')
            );

            $tag  = $tagRepository->findOneBy(['id' => $slugtag]);
        
            $queryBuilder = $articleRepository->createQueryBuilder('article')
                ->orderBy('article.createdAt', 'DESC')
                ->where('article.published = TRUE'); 
        
                if ($tag) {
                    $queryBuilder
                        ->andWhere(':tag MEMBER OF article.Tag')
                        ->setParameter('tag', $tag);
                }
        
            $pagerfanta = new Pagerfanta(new QueryAdapter($queryBuilder));
            $pagerfanta->setMaxPerPage(12);
            $pagerfanta->setCurrentPage($request->query->get('page', 1));

            if ($request->query->get('preview')) {
                return $this->render('article/_searchPreviewTag.html.twig', [
                    'pager' => $pagerfanta,
                ]);
            }

            $tags = $tagRepository->findAll();

            return $this->render('article/tag.html.twig', [
                'tags' => $tags,
                'tag' => $tag,
                'pager' => $pagerfanta,
            ]);
        }

}
