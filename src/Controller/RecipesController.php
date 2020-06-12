<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class RecipesController extends AbstractController
{

    /**
     * @Route("/recipes", name="get_all_recipes", methods={"GET"})
     * @param RecipeRepository $recipeRepository
     * @param SerializerInterface $normalizer
     * @return false|string
     */
    public function getAll(RecipeRepository $recipeRepository,SerializerInterface $normalizer)
    {
        $recipe = $recipeRepository->findAll();

        if(!$recipe){
            $response = new Response("Resource not found", 404, [
                "Content-Type" => "application/json"
            ]);
            return $response;
        }
        $recipeNormalize = $normalizer->normalize($recipe, "json");

        $response = new Response(json_encode($recipeNormalize), 200, [
            "Content-Type" => "application/json"
            ]);
        return $response;

    }

    /**
     * @Route("/recipes", name="create_recipes", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function createOne(Request $request, SerializerInterface $serializer, EntityManagerInterface $em){

        $jsonContent = $request->getContent();
        try{
            $recipe = $serializer->deserialize($jsonContent, Recipe::class, 'json');

            $em->persist($recipe);
            $em->flush();
            return $this->json([
                'status' => 201,
                'message' => 'Resource created'
            ], 201);

        }catch(NotEncodableValueException $e){
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/recipe/edit/{id}", name="update_one_recipe", methods={"POST"})
     */
    public function updateOne($id, RecipeRepository $recipeRepository, SerializerInterface $serializer, Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            $response = new Response("Resource not found", 404, [
                "Content-Type" => "application/json"
            ]);
            return $response;
        }
        try{
            $reqContent = $request->getContent();
            $recipeRequest = $serializer->deserialize($reqContent, Recipe::class, 'json');

            if($recipeRequest->getTitle() != "") $recipe->setTitle($recipeRequest->getTitle());
            if($recipeRequest->getSubtitle() != "") $recipe->setSubtitle($recipeRequest->getSubtitle());
            if($recipeRequest->getIngredient() != "") $recipe->setIngredient($recipeRequest->getIngredient());

            $entityManager->flush();

            return $this->json([
                'status' => 200,
                'message' => 'Resource Updated successfully'
            ], 200);

        }catch(NotEncodableValueException $e){
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

?>