<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Spryker\DecimalObject\Decimal;

interface ProductOfferStockFacadeInterface
{
    /**
     * Specification:
     * - Checks if the product offer stock with the provided product offer reference and store id is never out of stock.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return bool
     */
    public function isProductOfferNeverOutOfStock(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): bool;

    /**
     * Specification:
     * - Retrieves product offer stock from database for provided store.
     * - Expects ProductOfferStockRequestTransfer.store to be provided.
     * - Expects ProductOfferStockRequestTransfer.productOfferReference to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getProductOfferStock(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): Decimal;
}
