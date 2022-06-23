<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
// use App\Security\JwtAuthenticator;
// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;

class AuthController extends AbstractController implements UserInterface
{
    /**
     * @Route("/api/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $secret = $request->get('secret');
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setSecret($passwordHasher->hashPassword($user, $secret));
        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();
        return $this->json([
            'user' => $user->getEmail()
        ]);
    }

    public function getIdentifier():string
    {
        return $this->email;
    }

    public function getRoles(): array {
        return array('ROLE_USER');
    }
    
    public function eraseCredentials() {}
    
    public function getUserIdentifier(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->secret;
    }

    public function getPasswordHasherName(): ?string
    {
        return null; // use the default hasher
    }

}
