<?php

declare(strict_types=1);

namespace App\Model;


use App\Enum\OrderStatus;
use App\NameInterface\OrderDataNameInterface;
use App\NameInterface\ParameterInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final class OrderData
{
    #[SerializedName(OrderDataNameInterface::ID)]
    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Type(ParameterInterface::STRING)]
    public string $id;

    #[SerializedName(OrderDataNameInterface::CREATED_AT)]
    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Type(ParameterInterface::STRING)]
    #[Assert\DateTime(format: 'Y-m-d H:i:s')]
    public string $createdAt;

    #[SerializedName(OrderDataNameInterface::STATUS)]
    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Type(ParameterInterface::STRING)]
    #[Assert\Choice(callback: [OrderStatus::class, 'values'])]
    public string $status;

    #[SerializedName(OrderDataNameInterface::TOTAL)]
    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Type(ParameterInterface::FLOAT)]
    #[Assert\Positive]
    public float $total;

    /** @var OrderItemData[] */
    #[SerializedName(OrderDataNameInterface::ITEMS)]
    #[Assert\Valid]
    #[Assert\NotBlank(allowNull: false)]
    public array $items;

    public function updateTotalFromItems(): self
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getPrice() * $item->getQuantity();
        }

        $this->total = $total;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

}
