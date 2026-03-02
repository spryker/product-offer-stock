<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ProductOfferStock\ProductOfferStockConfig getConfig()
 */
class ProductOfferStockDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_STOCK_TRANSFER_PRODUCT_OFFER_STOCK_EXPANDER = 'PLUGINS_STOCK_TRANSFER_PRODUCT_OFFER_STOCK_EXPANDER';

    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addStockTransferProductOfferStockExpanderPlugins($container);

        return $container;
    }

    protected function addStockTransferProductOfferStockExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STOCK_TRANSFER_PRODUCT_OFFER_STOCK_EXPANDER, function () {
            return $this->getStockTransferExpanderPluginCollection();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductOfferStockExtension\Dependency\Plugin\StockTransferProductOfferStockExpanderPluginInterface>
     */
    protected function getStockTransferExpanderPluginCollection(): array
    {
        return [];
    }
}
