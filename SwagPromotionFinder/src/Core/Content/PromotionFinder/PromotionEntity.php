<?php
declare(strict_types=1);

namespace SwagPromotionFinder\Core\Content\PromotionFinder;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class PromotionEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $discountRate;

    /**
     * @var string
     */
    protected $startDate;

    /**
     * @var string
     */
    protected $expiredDate;


    /**
     * @var ProductEntity|null
     */
    protected $product;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getDiscountRate(): int
    {
        return $this->discountRate;
    }

    /**
     * @param int $discountRate
     */
    public function setDiscountRate(int $discountRate): void
    {
        $this->discountRate = $discountRate;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    /**
     * @param string $startDate
     */
    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string
     */
    public function getExpiredDate(): string
    {
        return $this->expiredDate;
    }

    /**
     * @param string $expiredDate
     */
    public function setExpiredDate(string $expiredDate): void
    {
        $this->expiredDate = $expiredDate;
    }

    /**
     * @return ProductEntity|null
     */
    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    /**
     * @param ProductEntity|null $product
     */
    public function setProduct(?ProductEntity $product): void
    {
        $this->product = $product;
    }
}
