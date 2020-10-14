<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DocumentationController extends AbstractController
{
    /**
     * @Route("/", name="documentation_redirect")
     * @Route("api/", name="documentation_redirect_Bis")
     */
    public function home_redirect()
    {
      return $this-> redirectToRoute('documentation');
    }

    /**
     * @Route("api/v1/", name="documentation")
     */
    public function home()
    {
        return $this->render('documentation/index.html.twig');
    }
}
