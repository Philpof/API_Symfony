<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ElementRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class ApiElementController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/api/element", name="api_element_index", methods={"GET"})
     */
    public function index(ElementRepository $elementRepository, NormalizerInterface $normalizer)
    {
        $elements = $elementRepository->findAll();

        $elementsNormalized = $normalizer->normalize($elements, null, ['groups' => 'element:read']);

        $json = json_encode($elementsNormalized);

        dd($json, $elements);

        return $this->render('api_element/index.html.twig', [
            'controller_name' => 'ApiElementController',
        ]);
    }
}
