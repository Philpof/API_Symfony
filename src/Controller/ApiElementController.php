<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ElementRepository;
// use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Element;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiElementController extends AbstractController
{
    /**
     * @Route("ZombiGo/v1/", name="home")
     */
    public function home()
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("ZombiGo/v1/element", name="api_element_index", methods={"GET"})
     */
    public function index(ElementRepository $elementRepository)
    {
          // A - Méthode décomposée :
                  // On récupère tous les éléments dans la variable '$elements', ce qui va nous servir après (Dans le A uniquement):
                      // $elements = $elementRepository->findAll();

              // a - Normalizer Vs. Serializer :
                  // 1ère version : on peut utiliser "NormalizerInterface $normalizer" en paramètre  de "index" (ligne 25) puis les 2 lignes ci-dessous pour normaliser puis encoder en .json
                  // La fonction "json_encode" permet d'encoder en .json et le tableau en 3ème paramètre de "normalize" permet de prendre uniquement les éléments du groupe "element:read" défini dans "Entity/Element.php" :
                      // $elementsNormalized = $normalizer->normalize($elements, null, ['groups' => 'element:read']);
                      // $json = json_encode($elementsNormalized);

                      // dd($elements, $elementsNormalized, $json);

                  // 2nd version : on peut aussi passer directemnt par "SerializerInterface $serializer" en paramètre de "index" puis normaliser et encoder en même temps. dans ce cas, il faut indiquer, en 2ème paramètre le format d'encadage voulu, ici c'est 'json' :
                      // $json = $serializer->serialize($elements, 'json', ['groups' => 'element:read']);

              // b - Response Vs. JsonResponse :
                  // A ce moment on utilise le "return" ci-dessous mais on va le changer en créant une variable '$response' :
                      // return $this->render('api_element/index.html.twig', [
                      //     'controller_name' => 'API - Element',
                      //     'elements' => $json
                      // ]);

                  // Pour le "reponse", on peut faire comme ci-dessous :
                      // $response = new Response($json, 200, [
                      //   "Content-Type" => "application/json"
                      // ]);
                      // return $response;

                  // Ou passer par "JsonResponse" qui est une classe enfant de "Response" adapté au json. Le tableau est donc vide et on met "true" pour indiquer que '$json' est déjà au format 'json' :
                      // $response = new JsonResponse($json, 200, [], true);
                      // return $response;

          // B - Méthode tout-en-un :
              // On utilise la fonction "json()" qui fait tout-en-un (Serialisation et 'JsonResponse') + on factorise le code en incorparant directement "$elementRepository->findAll()" sans passer par "$elements" + on retourne le résultat directement sans passer par la variable '$response' :

        return $this->json($elementRepository->findAll(), 200, [], ['groups' => 'element:read']);
    }

    /**
     * @Route("ZombiGo/v1/element/{id}", name="api_element_show", methods={"GET"})
     */
    public function show($id, ElementRepository $elementRepository)
    {
        return $this->json($elementRepository->find($id), 200, [], ['groups' => 'element:read']);
    }

    /**
     * @Route("ZombiGo/v1/element", name="api_element_store", methods={"POST"})
     */
    public function store(Request $request, SerializerInterface $serializer, EntityManagerInterface $emi, ValidatorInterface $validator)
    {
      $jsonReceived = $request->getContent();

      try {
        $element = $serializer->deserialize($jsonReceived, Element::class, 'json');

        $errors = $validator->validate($element);

        if (count($errors) > 0) {
          return $this->json($errors, 400);
        }

        $emi->persist($element);
        $emi->flush();

        return $this->json($jsonReceived, 201, [], ['groups' => 'element:read']);

      } catch (NotEncodableValueException $e) {
        return $this->json([
          'status' => 400,
          'message' => $e->getMessage()
        ], 400);
      }
    }
}
