<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Siganushka\AdminBundle\Form\UserType;
use Siganushka\AdminBundle\Repository\UserRepository;
use Siganushka\GenericBundle\Exception\FormErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin/users", methods={"GET"})
     */
    public function getCollection(Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->userRepository->createQueryBuilder('u');

        $page = $request->query->getInt('page', 1);
        $size = $request->query->getInt('size', 10);

        $pagination = $paginator->paginate($queryBuilder, $page, $size);

        return $this->createResponse($pagination);
    }

    /**
     * @Route("/admin/users", methods={"POST"})
     */
    public function postCollection(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entity = $this->userRepository->createNew();
        $entity->setEnabled(true);

        $form = $this->createForm(UserType::class, $entity);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            throw new FormErrorException($form);
        }

        $entityManager->persist($entity);
        $entityManager->flush();

        return $this->createResponse($entity);
    }

    /**
     * @Route("/admin/users/{id<\d+>}", methods={"GET"})
     */
    public function getItem(int $id): Response
    {
        $entity = $this->userRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('Resource with value "%d" not found.', $id));
        }

        return $this->createResponse($entity);
    }

    /**
     * @Route("/admin/users/{id<\d+>}", methods={"PUT", "PATCH"})
     */
    public function putItem(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $entity = $this->userRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('Resource with value "%d" not found.', $id));
        }

        $form = $this->createForm(UserType::class, $entity);
        $form->submit($request->request->all(), !$request->isMethod('PATCH'));

        if (!$form->isValid()) {
            throw new FormErrorException($form);
        }

        $entityManager->flush();

        return $this->createResponse($entity);
    }

    /**
     * @Route("/admin/users/{id<\d+>}", methods={"DELETE"})
     */
    public function deleteItem(EntityManagerInterface $entityManager, int $id): Response
    {
        $entity = $this->userRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('Resource with value "%d" not found.', $id));
        }

        $entityManager->remove($entity);
        $entityManager->flush();

        // 204 no content response
        return $this->createResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param mixed $data
     */
    protected function createResponse($data = null, int $statusCode = Response::HTTP_OK, array $headers = []): Response
    {
        $attributes = ['id', 'identifier', 'enabled', 'updatedAt', 'createdAt'];

        return $this->json($data, $statusCode, $headers, compact('attributes'));
    }
}
