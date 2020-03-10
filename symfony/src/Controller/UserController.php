<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserGetType;
use App\Form\UserUpdateBalanceType;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class UserController extends AbstractFOSRestController
{

    /**
     * Get users
     * @Rest\Get("/users")
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     */
    public function getUsers(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserGetType::class);

        $form->submit($request->query->all());
        if ($form->isValid()) {
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $users = $userRepository->getAllUsers($offset, $limit);
            $view = $this->view($users, 200);
            return $this->handleView($view);
        } else {
            $errors = $this->getErrorMessages($form);
            return $this->handleException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'An error has occurred while processing your request, make sure your data is valid',
                $errors
            );
        }
    }

    /**
     * Create user
     * @Rest\Post("/users")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @return Response
     */
    public function createUser(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response
    {
        $user = new User;
        $form = $this->createForm(UserCreateType::class, $user);
        $userPersonalInfo = json_decode($request->getContent(),true);

        $form->submit($userPersonalInfo);
        if ($form->isValid()) {
            $user = $form->getData();

            $user_id = $user->getUserId();
            $isExist = $userRepository->find($user_id);
            if ($isExist) {
                return $this->handleException(
                    Response::HTTP_CONFLICT,
                    'Attempt to create user with existed ID: ' . $user_id,
                    []
                );
            }

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->handleView($this->view(null,Response::HTTP_CREATED));
        } else {
            $errors = $this->getErrorMessages($form);
            return $this->handleException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'An error has occurred while processing your request, make sure your data is valid',
                $errors
            );
        }
    }

    /**
     * Update user
     * @Rest\Put("/users/{user_id}")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param $user_id
     * @return Response
     */
    public function updateUser(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        string $user_id
    ): Response
    {
        $user = $userRepository->find($user_id);
        if (!$user) {
            return $this->handleException(
                Response::HTTP_NOT_FOUND,
                'User with ID: ' . $user_id . ' is not found',
                []
            );
        }

        $form = $this->createForm(UserUpdateType::class, $user);
        $userPersonalInfo = json_decode($request->getContent(),true);

        $form->submit($userPersonalInfo);
        if ($form->isValid()) {
            $entityManager->flush();
            return $this->handleView($this->view(null,Response::HTTP_NO_CONTENT));
        } else {
            $errors = $this->getErrorMessages($form);
            return $this->handleException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'An error has occurred while processing your request, make sure your data is valid',
                $errors
            );
        }
    }

    /**
     * Update user balance
     * @Rest\Post("/users/{user_id}/recharge")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param string $user_id
     * @return Response
     */
    public function updateUserBalance(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        string $user_id
    ): Response
    {
        $user = $userRepository->find($user_id);
        if (!$user) {
            return $this->handleException(
                Response::HTTP_NOT_FOUND,
                'User with ID: ' . $user_id . ' is not found',
                []
            );
        }

        $form = $this->createForm(UserUpdateBalanceType::class, $user);
        $transaction = json_decode($request->getContent(),true);

        $form->submit($transaction);
        if ($form->isValid()) {
            $entityManager->flush();
            return $this->handleView($this->view(null,Response::HTTP_NO_CONTENT));
        } else {
            $errors = $this->getErrorMessages($form);
            return $this->handleException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'An error has occurred while processing your request, make sure your data is valid',
                $errors
            );
        }
    }

    /**
     * Get all errors from
     * the form
     * @param FormInterface $form
     * @return array
     */
    private function getErrorMessages(FormInterface $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors[$form->getName()][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $field) {
            if (!$field->isValid()) {
                $errors[$field->getName()] = $this->getErrorMessages($field);
            }
        }

        return $errors;
    }

    /**
     * Create JSON response with
     * status code, message and errors
     * @param int $http_status_code
     * @param string $message
     * @param array $errors
     * @return Response
     */
    private function handleException(int $http_status_code, string $message, array $errors)
    {
        return new Response(
            json_encode(
                array(
                    'http_status_code' => $http_status_code,
                    'message' => $message,
                    'errors' => $errors
                )
            ),
            $http_status_code
        );
    }
}
