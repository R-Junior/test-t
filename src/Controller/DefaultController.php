<?php

namespace App\Controller;

use App\Entity\Catalog;
use App\Entity\Comment;
use App\Entity\Product;
use App\Form\CommentType;
use App\Repository\CatalogRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     *
     * @param CatalogRepository $catalogRepository
     * @return Response
     */
    public function index(CatalogRepository $catalogRepository): Response
    {
        return $this->render(
            'default/index.html.twig',
            [
                'catalogs' => $catalogRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/catalog/{catalog}", name="catalog_info")
     *
     * @param Catalog $catalog
     * @return Response
     */
    public function catalogList(Catalog $catalog): Response
    {
        return $this->render(
            'default/catalog-info.html.twig',
            [
                'catalog' => $catalog,
            ]
        );
    }

    /**
     * @Route("/catalog/{catalog}/{product}", name="product_info")
     *
     * @param Catalog $catalog
     * @param Product $product
     * @param ProductRepository $productRepository
     * @param Request $request
     * @return Response
     */
    public function productInfo(
        Catalog $catalog,
        Product $product,
        ProductRepository $productRepository,
        Request $request
    ): Response {
        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            /** @var Comment $comment */
            $comment = $form->getData();
            $comment->setProduct($product);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->render(
            'default/product-info.html.twig',
            [
                'product' => $productRepository->findOneBy(
                    [
                        'id' => $product->getId(),
                        'catalog' => $catalog,
                    ]
                ),
                'form' => $form->createView(),
            ]
        );
    }
}
