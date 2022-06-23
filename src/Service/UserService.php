<?php

namespace App\Service;

use Doctrine\DBAL\Driver\Connection;
use App\Repository\CountryRepository;

class UserService 
{
    private $userRepository;

    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Finds all users
     */
    public function findAll() {

        $data = $this->userRepository->findAll();

        return $data;
    }
    
}
