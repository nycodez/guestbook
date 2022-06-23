<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $user_id;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $total;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $submitted_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $invoice_created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $invoice_sent_at;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getSubmittedAt(): ?\DateTime
    {
        return $this->submitted_at;
    }

    public function setSubmittedAt(?\DateTime $submitted_at): self
    {
        $this->submitted_at = $submitted_at;

        return $this;
    }

    public function getInvoiceCreatedAt(): ?\DateTime
    {
        return $this->invoice_created_at;
    }

    public function setInvoiceCreatedAt(?\DateTime $invoice_created_at): self
    {
        $this->invoice_created_at = $invoice_created_at;

        return $this;
    }

    public function getInvoiceSentAt(): ?\DateTime
    {
        return $this->invoice_sent_at;
    }

    public function setInvoiceSentAt(?\DateTime $invoice_sent_at): self
    {
        $this->invoice_sent_at = $invoice_sent_at;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addOrderId($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeOrderId($this);
        }

        return $this;
    }
}
