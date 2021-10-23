<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Form\MealType;
use Baldeweg\Bundle\ApiBundle\AbstractApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/meal")
 */
class MealController extends AbstractApiController
{
    private $fields = ['id', 'name', 'description', 'price', 'deleted'];

    /**
     * @Route("/", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(): JsonResponse
    {
        return $this->setResponse()->collection(
            $this->fields,
            $this->getDoctrine()->getRepository(Meal::class)->findByDeleted(
                false,
            )
        );
    }

    /**
     * @Route("/{meal}", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function show(Meal $meal): JsonResponse
    {
        return $this->setResponse()->single($this->fields, $meal);
    }

    /**
     * @Route("/new", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function new(Request $request): JsonResponse
    {
        $meal = new Meal();
        $form = $this->createForm(MealType::class, $meal);

        $form->submit(
            $this->submitForm($request)
        );
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meal);
            $em->flush();

            return $this->setResponse()->single($this->fields, $meal);
        }

        return $this->setResponse()->invalid();
    }

    /**
     * @Route("/{meal}", methods={"PUT"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(Request $request, Meal $meal): JsonResponse
    {
        $form = $this->createForm(MealType::class, $meal);

        $form->submit(
            $this->submitForm($request)
        );
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->setResponse()->single($this->fields, $meal);
        }

        return $this->setResponse()->invalid();
    }

    /**
     * @Route("/{meal}", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function delete(Meal $meal): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($meal);
        $em->flush();

        return $this->setResponse()->deleted();
    }
}
