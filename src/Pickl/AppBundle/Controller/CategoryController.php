<?php

namespace Pickl\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Config\Definition\Exception\Exception;

class CategoryController extends Controller implements TokenAuthenticatedController  {

    public function getCategoryAction($slug, $page) {

        if($page < 1)
            throw new NotFoundHttpException('Page number "'.$page.'" not found');

        $nbPerPage = $this->container->getParameter('nb_projects_per_page_category');

        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('PicklAppBundle:Category')->getCategoryWithSlug($slug);

        if($category === null)
            throw new NotFoundHttpException('Category with slug "'.$slug.'" not found');

        $projects = $em->getRepository('PicklAppBundle:Project')->findAllProjectsWithCategory($category->getSlug(), $page, $nbPerPage);

        $nbPages = ceil(count($projects)/$nbPerPage);

        if($page > $nbPages && $nbPages != 0)
            throw new Exception('Page number "'.$page.'" not found');

       // if($nbPages == 0)
         //   return $this->redirectToRoute('pickl_app_homepage');

        return $this->render(
            'PicklAppBundle:Category:index.html.twig',
            array(
                'category' => $category,
                'projects' => $projects,
                'nbPages' => $nbPages,
                'page' => $page
            )
        );
    }
}