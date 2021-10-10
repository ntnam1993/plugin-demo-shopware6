<?php
declare(strict_types=1);

namespace SwagShopFinder\Core\Content\ShopFinder;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                         add(CustomTranslationEntity $entity)
 * @method void                         set(string $key, CustomTranslationEntity $entity)
 * @method CustomTranslationEntity[]    getIterator()
 * @method CustomTranslationEntity[]    getElements()
 * @method CustomTranslationEntity|null get(string $key)
 * @method CustomTranslationEntity|null first()
 * @method CustomTranslationEntity|null last()
 */
class ShopFinderCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ShopFinderEntity::class;
    }
}
