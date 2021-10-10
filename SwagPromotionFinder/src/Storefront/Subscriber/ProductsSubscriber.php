<?php declare(strict_types=1);

namespace SwagPromotionFinder\Storefront\Subscriber;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use SwagPromotionFinder\Core\Content\PromotionFinder\PromotionCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\Content\Product\ProductEvents;

class ProductsSubscriber implements EventSubscriberInterface
{
    private EntityRepositoryInterface $promotionRepository;
    protected SystemConfigService $systemConfigService;

    public function __construct(
        EntityRepositoryInterface $promotionRepository,
        SystemConfigService $systemConfigService
    ) {
        $this->promotionRepository = $promotionRepository;
        $this->systemConfigService = $systemConfigService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvents::PRODUCT_LOADED_EVENT => 'onProductsLoaded'
        ];
    }

    public function onProductsLoaded(EntityLoadedEvent $event): void
    {
        if (!$this->systemConfigService->get('SwagPromotionFinder.config.showPromotion')) {
            return;
        }
        /** @var ProductEntity $productEntity */
        foreach ($event->getEntities() as $productEntity) {
            $promotion = $this->fetchPromotion($event->getContext(), $productEntity->getId());
            $productEntity->addExtension('promotion', $promotion);
        }
    }

    /**
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
}
