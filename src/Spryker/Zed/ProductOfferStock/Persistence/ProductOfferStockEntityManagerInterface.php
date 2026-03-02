<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Persistence;

use Generated\Shared\Transfer\ProductOfferStockTransfer;

interface ProductOfferStockEntityManagerInterface
{
    public function create(ProductOfferStockTransfer $productOfferStockTransfer): ProductOfferStockTransfer;

    public function update(ProductOfferStockTransfer $productOfferStockTransfer): ProductOfferStockTransfer;
}
