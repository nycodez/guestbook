<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
	/**
 	* @see UserInterface
 	*/
     public function getSalt() {}
 
     /**
      * @see UserInterface
      */
     public function eraseCredentials() {}
      
}
