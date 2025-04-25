<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\OrderStatus;
use App\NameInterface\ParameterInterface;
use App\Repository\OrderRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 't_order')]
final class Order
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(name: 'created_at', type: ParameterInterface::DATETIME, unique: false, nullable: false)]
    private ?DateTime $createdAt = null;

    #[ORM\Column(name: 'status', type: 'string', nullable: false, enumType: OrderStatus::class)]
    private ?OrderStatus $status = null;

    #[ORM\Column(name: 'total', type: ParameterInterface::DECIMAL , precision: 10, scale: 2,  nullable: false)]
    private ?float $total = null;

    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?Collection $items = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?OrderStatus
    {
        return $this->status;
    }

    public function setStatus(?OrderStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getItems(): ?Collection
    {
        return $this->items;
    }

    public function setItems(?Collection $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function addItem(OrderItem $item): Order
    {
        if (is_null($this->items)) {
            $this->items = new ArrayCollection();
        }

        $this->items->add($item);

        return $this;
    }

}
