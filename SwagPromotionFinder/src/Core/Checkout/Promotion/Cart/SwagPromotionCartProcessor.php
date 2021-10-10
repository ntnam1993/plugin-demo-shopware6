<?php declare(strict_types=1);

namespace SwagPromotionFinder\Core\Checkout\Promotion\Cart;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\Price\PercentagePriceCalculator;
use Shopware\Core\Checkout\Cart\Price\QuantityPriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\PercentagePriceDefinition;
use Shopware\Core\Checkout\Cart\Price\Struct\PriceCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use SwagPromotionFinder\Core\Content\PromotionFinder\PromotionCollection;

class SwagPromotionCartProcessor implements CartProcessorInterface, CartDataCollectorInterface
{
    public const TYPE = 'product';
    public const DISCOUNT_TYPE = 'swagpromotion-discount';
    public const DATA_KEY = 'swag_promotion-';

    /**
     * @var EntityRepositoryInterface
     */
    private $promotionRepository;

    /**
     * @var PercentagePriceCalculator
     */
    private $percentagePriceCalculator;

    /**
     * @var QuantityPriceCalculator
     */
    private $quantityPriceCalculator;

    public function __construct(
        EntityRepositoryInterface $promotionRepository,
        PercentagePriceCalculator $percentagePriceCalculator,
        QuantityPriceCalculator $quantityPriceCalculator
    ) {
        $this->promotionRepository = $promotionRepository;
        $this->percentagePriceCalculator = $percentagePriceCalculator;
        $this->quantityPriceCalculator = $quantityPriceCalculator;
    }

    public function collect(CartDataCollection $data, Cart $original, SalesChannelContext $context, CartBehavior $behavior): void
    {
        /** @var LineItemCollection $productLineItems */
        $productLineItems = $original->getLineItems()->filterType(self::TYPE);

        // no product in cart? exit
        if (\count($productLineItems) === 0) {
            return;
        }

        foreach ($productLineItems as $productLineItem) {

            $promotions = $this->fetchPromotion($context->getContext(), $productLineItem->getReferencedId());
            $selectedPromotions = $this->selectPromotion($promotions);

            // add promotion discount for each product
            $this->addDiscount($productLineItem, $selectedPromotions, $context);
        }
    }

    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {
        // collect all product in cart
        $productLineItems = $original->getLineItems()->filterType(self::TYPE);

        if (\count($productLineItems) === 0) {
            return;
        }

        foreach ($productLineItems as $productLineItem) {
            // calculate all promotion product price
            $this->calculateChildProductPrices($productLineItem, $context);

            // calculate and  product price
            $productLineItem->setPrice(
                (new PriceCollection([$productLineItem->getPrice(), $productLineItem->getChildren()->getPrices()->sum()]))->sum()
            );
        }
    }

    /**
     * Fetches all Promotions that are not already stored in data
     * @param Context $context
     * @param string $productId
     * @return PromotionCollection
     */
    public function fetchPromotion(Context $context, string $productId): PromotionCollection
    {
        $criteria = new Criteria();

        $criteria->addFilter(new EqualsFilter('productId', $productId));

        /** @var PromotionCollection $promotionCollection */
        $promotionCollection = $this->promotionRepository->search($criteria, $context)->getEntities();

        return $promotionCollection;
    }

    private function addDiscount(LineItem $productLineItem, PromotionCollection $promotions, SalesChannelContext $context): void
    {
        foreach ($promotions as $promotion) {
            $label = sprintf('Percentage promotion voucher (%s%%)', $promotion->getDiscountRate());

            $discount = new LineItem(
                $promotion->getId() . '-discount',
                self::DISCOUNT_TYPE,
                $promotion->getId()
            );

            $discount->setStackable(true)
                ->setLabel($label)
                ->setCover($productLineItem->getCover())
                ->setQuantity($productLineItem->getQuantity())
                ->setGood(false);

            if ($discount) {
                $productLineItem->addChild($discount);
            }
        }
    }

    private function calculateChildProductPrices(LineItem $productLineItem, SalesChannelContext $context): void
    {
        /** @var LineItemCollection $products */
        $promotions = $productLineItem->getChildren()->filterType(self::DISCOUNT_TYPE);

        $productPrice = $productLineItem->getPrice();

        foreach ($promotions as $promotion) {
            $promotion->setQuantity($productLineItem->getQuantity());

            // get from data of CartDataCollection
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('id', $promotion->getReferencedId()));
            $promotionEntity = $this->promotionRepository->search($criteria, $context->getContext())->getEntities()->first();

            $discountPriceDefinition = new PercentagePriceDefinition($promotionEntity->getDiscountRate() * -1, $context->getContext()->getCurrencyPrecision());;
            $promotion->setPriceDefinition($discountPriceDefinition);
            $promotion->setPrice($this->percentagePriceCalculator->calculate($discountPriceDefinition->getPercentage(), new PriceCollection([$productPrice]), $context));
        }
    }

    /**
     * Filter promotions satisfies the condition on system config
     *
     * @param PromotionCollection $promotionCollection
     * @return PromotionCollection
     */
    public function selectPromotion(PromotionCollection $promotionCollection): PromotionCollection
    {
        // todo
        return $promotionCollection;
    }

}
