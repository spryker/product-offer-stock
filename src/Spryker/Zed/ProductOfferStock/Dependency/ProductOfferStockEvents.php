<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Dependency;

interface ProductOfferStockEvents
{
    /**
     * Specification:
     * - Represents spy_product_offer_stock entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STOCK_CREATE = 'Entity.spy_product_offer_stock.create';

    /**
     * Specification:
     * - Represents spy_product_offer_stock entity update.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STOCK_UPDATE = 'Entity.spy_product_offer_stock.update';
}
