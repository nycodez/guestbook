<?php

namespace App\Service;

use Doctrine\DBAL\Driver\Connection;
use App\Repository\CountryRepository;

class OrderService 
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository) 
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Finds all orders
     */
    public function findAll() {

        $data = $this->orderRepository->findAll();

        return $data;
    }
    
}
