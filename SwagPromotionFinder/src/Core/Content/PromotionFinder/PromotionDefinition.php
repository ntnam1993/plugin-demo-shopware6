<?php
declare(strict_types=1);

namespace SwagPromotionFinder\Core\Content\PromotionFinder;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class PromotionDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'swag_promotion';
    }

    public function getCollectionClass(): string
    {
        return PromotionCollection::class;
    }

    public function getEntityClass(): string
    {
        return PromotionEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        /*
         * id() id
         * bool active
         * string name
         * integer discount_rate
         * string start_date
         * string expired_date
         * ManyToOneAssociation product to ProductDefinition
         */

        return  new  FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            new BoolField('active', 'active'),
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new IntField('discount_rate', 'discountRate'))->addFlags(new Required()),
            (new StringField('start_date', 'startDate'))->addFlags(new Required()),
            (new StringField('expired_date', 'expiredDate'))->addFlags(new Required()),
            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new FkField('product_id',  'productId', ProductDefinition::class),
            new ManyToOneAssociationField(
                'product',
                'product_id',
                ProductDefinition::class,
                'id',
                false,
            )
        ]);
    }
}
