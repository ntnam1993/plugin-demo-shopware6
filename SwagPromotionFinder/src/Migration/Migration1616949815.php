<?php declare(strict_types=1);

namespace SwagPromotionFinder\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1616949815 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1616949815;
    }

    public function update(Connection $connection): void
    {
        $connection->exec("
            CREATE TABLE IF NOT EXISTS `swag_promotion` (
                `id` BINARY(16) NOT NULL,
                `active` TINYINT(1) NULL DEFAULT '0',
                `name` VARCHAR(255) NULL,
                `discount_rate` INTEGER(3) NULL,
                `start_date` DATE NULL,
                `expired_date` DATE NULL,
                `product_id` BINARY(16) NULL,
                `product_version_id` BINARY(16) NOT NULL,
                `created_at` DATETIME(3),
                `updated_at` DATETIME(3),
                PRIMARY KEY (`id`),
                KEY `fk.swag_promotion.product_id` (`product_id`),
                CONSTRAINT `pk.swag_promotion.product_id` FOREIGN KEY (`product_id`)
                    REFERENCES `product` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
