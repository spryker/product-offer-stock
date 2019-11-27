<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Persistence;

use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOfferStock\Persistence\Propel\Mapper\ProductOfferStockMapper;
use Spryker\Zed\ProductOfferStock\Persistence\Propel\Mapper\ProductOfferStockMapperInterface;

/**
 * @method \Spryker\Zed\ProductOfferStock\ProductOfferStockConfig getConfig()
 */
class ProductOfferStockPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferStock\Persistence\Propel\Mapper\ProductOfferStockMapperInterface
     */
    public function createProductOfferStockMapper(): ProductOfferStockMapperInterface
    {
        return new ProductOfferStockMapper();
    }

    /**
     * @return \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery
     */
    public function createProductOfferStockPropelQuery(): SpyProductOfferStockQuery
    {
        return SpyProductOfferStockQuery::create();
    }
}