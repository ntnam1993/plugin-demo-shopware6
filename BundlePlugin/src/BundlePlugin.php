<?php declare(strict_types=1);

namespace BundlePlugin;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexerRegistry;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class BundlePlugin extends Plugin
{
    public function activate(ActivateContext $activateContext): void
    {
        $registry = $this->container->get(EntityIndexerRegistry::class);
        $registry->sendIndexingMessage(['product.indexer']);
    }

    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);
        $connection->executeUpdate('DROP TABLE IF EXISTS `swag_bundle_product`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `swag_bundle_translation`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `swag_bundle`');
        $connection->executeUpdate('ALTER TABLE `product` DROP COLUMN `bundles`');
    }
}
