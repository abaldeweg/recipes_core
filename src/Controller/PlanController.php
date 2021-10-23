<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Form\PlanType;
use Baldeweg\Bundle\ApiBundle\AbstractApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/plan")
 */
class PlanController extends AbstractApiController
{
    private $fields = ['id', 'year', 'week', 'name'];

    /**
     * @Route("/", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(): JsonResponse
    {
        return $this->setResponse()->collection(
            $this->fields,
            $this->getDoctrine()->getRepository(Plan::class)->findAll()
        );
    }

    /**
     * @Route("/find", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function find(Request $request): JsonResponse
    {
        return $this->setResponse()->single(
            $this->fields,
            $this->getDoctrine()->getRepository(Plan::class)->findOneBy([
                'year' => $request->get('year'),
                'week' => $request->get('week')
            ])
        );
    }

    /**
     * @Route("/{plan}", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function show(Plan $plan): JsonResponse
    {
        return $this->setResponse()->single($this->fields, $plan);
    }

    /**
     * @Route("/new", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function new(Request $request): JsonResponse
    {
        $plan = new Plan();
        $form = $this->createForm(PlanType::class, $plan);

        $form->submit(
            $this->submitForm($request)
        );
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($plan);
            $em->flush();

            return $this->setResponse()->single($this->fields, $plan);
        }

        return $this->setResponse()->invalid();
    }

    /**
     * @Route("/{plan}", methods={"PUT"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(Request $request, Plan $plan): JsonResponse
    {
        $form = $this->createForm(PlanType::class, $plan);

        $form->submit(
            $this->submitForm($request)
        );
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->setResponse()->single($this->fields, $plan);
        }

        return $this->setResponse()->invalid();
    }

    /**
     * @Route("/{plan}", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function delete(Plan $plan): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($plan);
        $em->flush();

        return $this->setResponse()->deleted();
    }
}
