<?php declare(strict_types=1);

namespace SwagPromotionFinder\Core\Api;

use Faker\Factory;
use Shopware\Core\Content\Product\Cart\ProductNotFoundError;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"api"})
 */
class PromotionController extends AbstractController
{
    /**
     * @var EntityRepositoryInterface
     */
    private $productRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $promotionRepository;

    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    /**
     * PromotionController constructor.
     * @param EntityRepositoryInterface $productRepository
     * @param EntityRepositoryInterface $promotionRepository
     * @param SystemConfigService $systemConfigService
     */
    public function __construct(
        EntityRepositoryInterface $productRepository,
        EntityRepositoryInterface $promotionRepository,
        SystemConfigService $systemConfigService
    ) {
        $this->productRepository = $productRepository;
        $this->promotionRepository = $promotionRepository;
        $this->systemConfigService = $systemConfigService;
    }

    /**
     * @Route("/api/v{version}/_action/swag-promotion/generate", name="api.custom.swag_promotion.generate", methods={"POST"})
     * @param Context $context
     * @return Response
     * @throws ProductNotFoundError
     */
    public function generate(Context $context): Response
    {
        $criteria = new Criteria();

        $maxPromotion = $this->systemConfigService->get('SwagPromotionFinder.config.maxPromotion');
        $faker = Factory::create();
        $productIds = $this->getActiveProductIds($context);
        $data = [];
        foreach ($productIds as $productId) {
            $numPromotion = rand(1, $maxPromotion);
            for ($i = 0; $i < $numPromotion; $i++) {
                $data[] = [
                    'id' => Uuid::randomHex(),
                    'active' => true,
                    'name' => $faker->name,
                    'discountRate' => rand(1,50),
                    'startDate' => date("Y-m-d", rand(time() - 30*24*60*60, time())),
                    'expiredDate' => date("Y-m-d", rand(time(), time() + 30*24*60*60)),
                    'productId' => $productId,
                ];
            }
        }
        $this->promotionRepository->create($data, $context);

        return new  Response('ok', Response::HTTP_OK);
    }

    /**
     * @param Context $context
     * @return array
     * @throws ProductNotFoundError
     */
    private function getActiveProductIds(Context $context): array
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', 1));
        $criteria->setLimit(10);

        $productIds = $this->productRepository->search($criteria, $context)->getEntities()->getIds();
        if ($productIds === null)
        {
            throw new ProductNotFoundError('');
        }
        return $productIds;
    }
}
