<?php

declare(strict_types=1);

namespace App\Model;

use App\NameInterface\OrderDataNameInterface;
use App\NameInterface\ParameterInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final class OrderItemData
{
    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Type(ParameterInterface::STRING)]
    #[SerializedName(OrderDataNameInterface::PRODUCT_ID)]
    public string $productId;

    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Type(ParameterInterface::STRING)]
    #[SerializedName(OrderDataNameInterface::PRODUCT_NAME)]
    public string $productName;

    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Type(ParameterInterface::FLOAT)]
    #[Assert\Positive]
    #[SerializedName(OrderDataNameInterface::PRICE)]
    public float $price;

    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Type(ParameterInterface::INT)]
    #[Assert\Positive]
    #[SerializedName(OrderDataNameInterface::QUANTITY)]
    public int $quantity;

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }


}
