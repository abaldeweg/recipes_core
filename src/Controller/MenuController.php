<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use Baldeweg\Bundle\ApiBundle\AbstractApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/menu")
 */
class MenuController extends AbstractApiController
{
    private $fields = ['id', 'meal.id', 'plan.id', 'day', 'course'];

    /**
     * @Route("/", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(): JsonResponse
    {
        return $this->setResponse()->collection(
            $this->fields,
            $this->getDoctrine()->getRepository(Menu::class)->findAll()
        );
    }

    /**
     * @Route("/find", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function find(Request $request): JsonResponse
    {
        return $this->setResponse()->collection(
            $this->fields,
            $this->getDoctrine()->getRepository(Menu::class)->findBy(
                ['plan' => $request->get('plan')],
                [
                    'day' => 'ASC',
                    'course' => 'ASC',
                ]
            )
        );
    }

    /**
     * @Route("/{menu}", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function show(Menu $menu): JsonResponse
    {
        return $this->setResponse()->single($this->fields, $menu);
    }

    /**
     * @Route("/new", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function new(Request $request): JsonResponse
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);

        $form->submit(
            $this->submitForm($request)
        );
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            return $this->setResponse()->single($this->fields, $menu);
        }

        return $this->setResponse()->invalid();
    }

    /**
     * @Route("/{menu}", methods={"PUT"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(Request $request, Menu $menu): JsonResponse
    {
        $form = $this->createForm(MenuType::class, $menu);

        $form->submit(
            $this->submitForm($request)
        );
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->setResponse()->single($this->fields, $menu);
        }

        return $this->setResponse()->invalid();
    }

    /**
     * @Route("/{menu}", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function delete(Menu $menu): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($menu);
        $em->flush();

        return $this->setResponse()->deleted();
    }
}
