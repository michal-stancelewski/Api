<?php

declare(strict_types=1);

namespace App\Entity;

use App\NameInterface\ParameterInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 't_order_item')]
final class OrderItem
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(name: 'product_id', type: ParameterInterface::STRING, length: 40, unique: false, nullable: false)]
    private ?string $productId = null;

    #[ORM\Column(name: 'product_name', type: ParameterInterface::STRING, length: 255, unique: false, nullable: false)]
    private ?string $productName = null;

    #[ORM\Column(name: 'price', type: ParameterInterface::DECIMAL, precision: 10, scale: 2, nullable: false)]
    private ?float $price = null;

    #[ORM\Column(name: 'quantity', type: ParameterInterface::INT, nullable: false)]
    private ?int $quantity = null;

    #[ORM\ManyToOne(Order::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\JoinColumn('order_id', 'id', nullable: false)]
    private ?Order $order = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(?string $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

}
