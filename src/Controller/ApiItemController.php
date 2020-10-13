<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ItemRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiItemController extends AbstractController
{
    /**
     * @Route("ZombiGo/v1/item", name="api_item_index", methods={"GET"})
     */
    public function index(ItemRepository $itemRepository)
    {
      return $this->json($itemRepository->findAll(), 200, [], ['groups' => 'item:read']);
  }

  /**
   * @Route("ZombiGo/v1/item/{id}", name="api_item_show", methods={"GET"})
   */
  public function show($id, ItemRepository $itemRepository)
  {
      return $this->json($itemRepository->find($id), 200, [], ['groups' => 'item:read']);
  }

  /**
   * @Route("ZombiGo/v1/item", name="api_item_store", methods={"POST"})
   */
  public function store(Request $request, SerializerInterface $serializer, EntityManagerInterface $emi, ValidatorInterface $validator)
  {
    $jsonReceived = $request->getContent();

    try {
      $item = $serializer->deserialize($jsonReceived, Item::class, 'json');

      $errors = $validator->validate($item);

      if (count($errors) > 0) {
        return $this->json($errors, 400);
      }

      $emi->persist($item);
      $emi->flush();

      return $this->json($jsonReceived, 201, [], ['groups' => 'item:read']);

    } catch (NotEncodableValueException $e) {
      return $this->json([
        'status' => 400,
        'message' => $e->getMessage()
      ], 400);
    }
  }
}
