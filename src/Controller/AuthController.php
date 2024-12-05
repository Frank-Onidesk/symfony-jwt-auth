<?php
// src/Controller/AuthController.php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use  Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{
   #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request,EntityManagerInterface $em,  JWTTokenManagerInterface $JWTManager, UserPasswordHasherInterface $passwordEncoder)
    {
        // Get the request data (email and password)
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['message' => 'Email and password are required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Find user by email
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            dump($user); // Debug: Check if the user is found
            return new JsonResponse(['message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$user) {
            return new JsonResponse(['message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Check if the password matches
        if (!$passwordEncoder->isPasswordValid($user, $password)) {
            dump($passwordEncoder->isPasswordValid($user, $password)); // Debug: Check if password is valid
            return new JsonResponse(['message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Generate JWT token
        $token = $JWTManager->create($user);

        // Return the token in response
        return new JsonResponse(['token' => $token]);
    }
}
