<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;
use Orm\Zed\Stock\Persistence\SpyStock;
use Propel\Runtime\Collection\ObjectCollection;

class ProductOfferStockMapper
{
    /**
     * @var array<int, \Generated\Shared\Transfer\StockTransfer>
     */
    protected static array $stockCache = [];

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock> $productOfferStockEntities
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer> $productOfferStockTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer>
     */
    public function mapProductOfferStockEntityCollectionToProductOfferStockTransfers(
        ObjectCollection $productOfferStockEntities,
        ArrayObject $productOfferStockTransfers
    ): ArrayObject {
        foreach ($productOfferStockEntities as $productOfferStockEntity) {
            $productOfferStockTransfers->append(
                $this->mapProductOfferStockEntityToProductOfferStockTransfer($productOfferStockEntity, new ProductOfferStockTransfer()),
            );
        }

        return $productOfferStockTransfers;
    }

    public function mapProductOfferStockEntityToProductOfferStockTransfer(
        SpyProductOfferStock $productOfferStockEntity,
        ProductOfferStockTransfer $productOfferStockTransfer
    ): ProductOfferStockTransfer {
        $productOfferStockTransfer->fromArray($productOfferStockEntity->toArray(), true);
        $productOfferStockTransfer->setIdProductOffer($productOfferStockEntity->getFkProductOffer());
        $productOfferStockTransfer->setProductOfferReference($productOfferStockEntity->getSpyProductOffer()->getProductOfferReference());
        $productOfferStockTransfer->setStock($this->getStockTransfer($productOfferStockEntity));

        return $productOfferStockTransfer;
    }

    public function mapProductOfferStockTransferToProductOfferStockEntity(
        ProductOfferStockTransfer $productOfferStockTransfer,
        SpyProductOfferStock $productOfferStockEntity
    ): SpyProductOfferStock {
        /** @var int $idProductOffer */
        $idProductOffer = $productOfferStockTransfer->getIdProductOffer();

        /** @var \Generated\Shared\Transfer\StockTransfer $stockTransfer */
        $stockTransfer = $productOfferStockTransfer->getStock();

        /** @var int $idStock */
        $idStock = $stockTransfer->getIdStock();

        $productOfferStockEntity->fromArray($productOfferStockTransfer->toArray(false));
        $productOfferStockEntity->setFkProductOffer($idProductOffer);
        $productOfferStockEntity->setFkStock($idStock);

        return $productOfferStockEntity;
    }

    protected function mapStockEntityToStockTransfer(SpyStock $stockEntity, StockTransfer $stockTransfer): StockTransfer
    {
        $stockTransfer->fromArray($stockEntity->toArray(), true);

        return $this->mapStockStoresToStockTransfer($stockEntity, $stockTransfer);
    }

    protected function mapStockStoresToStockTransfer(SpyStock $stockEntity, StockTransfer $stockTransfer): StockTransfer
    {
        $stockStoreEntities = $stockEntity->getStockStores();
        if ($stockStoreEntities->count() === 0) {
            return $stockTransfer;
        }

        $storeRelationTransfer = new StoreRelationTransfer();
        foreach ($stockStoreEntities as $stockStoreEntity) {
            $storeEntity = $stockStoreEntity->getStore();
            if ($storeEntity === null) {
                continue;
            }

            $storeRelationTransfer->addStores(
                (new StoreTransfer())->fromArray($storeEntity->toArray(), true),
            );
        }

        return $stockTransfer->setStoreRelation($storeRelationTransfer);
    }

    protected function getStockTransfer(SpyProductOfferStock $productOfferStockEntity): StockTransfer
    {
        $idStock = $productOfferStockEntity->getFkStock();
        if (isset(static::$stockCache[$idStock])) {
            return static::$stockCache[$idStock];
        }

        $stockTransfer = $this->mapStockEntityToStockTransfer($productOfferStockEntity->getStock(), new StockTransfer());

        if ($stockTransfer->getStoreRelation() !== null) {
            static::$stockCache[$idStock] = $stockTransfer;
        }

        return $stockTransfer;
    }
}
