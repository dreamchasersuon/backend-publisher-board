<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserUpdateBalanceType;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class UsersController extends AbstractFOSRestController
{

    /**
     * Get users
     * @Rest\Get("/users")
     * @param Request $request
     * @return Response
     */
    public function getUsersAction(Request $request, UserRepository $userRepository): Response
    {
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        if ($offset === null || $limit === null) {
            return $this->handleException(
                422,
                'An error has occurred while processing your request, make sure your data is valid'
            );
        }

        $users = $userRepository->getAllUsers($offset, $limit);
        $view = $this->view($users, 200);
        return $this->handleView($view);
    }

    /**
     * Create user
     * @Rest\Post("/users")
     * @param Request $request
     * @return Response
     */
    public function postUserAction(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = new User;
        $form = $this->createForm(UserCreateType::class, $user);
        $data=json_decode($request->getContent(),true);

        $form->submit($data);
        if ($form->isValid()) {
            $user = $form->getData();

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->handleView($this->view(['status'=>'ok'],Response::HTTP_CREATED));
        } else {
            return $this->handleException(
                422,
                'An error has occurred while processing your request, make sure your data is valid'
            );
        }
    }

    /**
     * Update user
     * @Rest\Put("/users/{user_id}")
     * @param Request $request
     * @return Response
     */
    public function putUserAction(
        User $user,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(UserUpdateType::class, $user);
        $data=json_decode($request->getContent(),true);

        $form->handleRequest($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->handleView($this->view(['status'=>'ok'],Response::HTTP_NO_CONTENT));
        } else {
            return $this->handleException(
                422,
                'An error has occurred while processing your request, make sure your data is valid'
            );
        }
    }

    /**
     * Update user balance
     * @Rest\Post("/users/{user_id}/recharge")
     * @param Request $request
     * @return Response
     */
    public function postUserBalanceAction(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = new User;
        $form = $this->createForm(UserUpdateBalanceType::class, $user);
        $data=json_decode($request->getContent(),true);

        $form->handleRequest($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->handleView($this->view(['status'=>'ok'],Response::HTTP_NO_CONTENT));
        } else {
            return $this->handleException(
                422,
                'An error has occurred while processing your request, make sure your data is valid'
            );
        }
    }

    private function handleException(int $http_status_code, string $message)
    {
        return new Response(
            json_encode(
                array(
                    'http_status_code' => $http_status_code,
                    'message' => $message
                )
            )
        );
    }
}
