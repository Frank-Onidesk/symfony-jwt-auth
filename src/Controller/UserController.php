<?php
// src/Controller/UserController.php

namespace App\Controller;

use ApiPlatform\Symfony\Validator\Validator;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;


class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response | JsonResponse
    {
        // Retrieve data from request (for example, JSON or form data)
        $data = json_decode($request->getContent(), true);


        $validator = Validation::createValidator();
        $violations = $validator->validate(
            $data,
            [new Assert\Email(), new Assert\NotBlank()]
        );

        if (count($violations) > 0) {

            foreach ($violations as $violation) {
                $errors[] = [
                 
                    'property' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage()

                ] ;
                 
            
                
            }

            return $this->json([

                'errors' =>  $errors,
                'status' => 'error',
                'message' => 'Validation failed'

            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Create a new User instance
        $user = new User();
        $user->setEmail($data['email']);



        // Hash the password
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $data['password']
        );

        // Set the hashed password to the user entity
        $user->setPassword($hashedPassword);

        // Save the user to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response('User registered successfully', Response::HTTP_CREATED);
    }




    #[Route('/api/protected', name: 'app_protected', methods: ['GET'])]
    public function protectedArea(): Response
    {

        return new Response('Welcome to protected area using Jason Web T', 200);
    }
}
