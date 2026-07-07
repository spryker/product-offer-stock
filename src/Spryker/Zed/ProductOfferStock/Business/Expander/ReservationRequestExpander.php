<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business\Expander;

use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ProductOfferStock\Business\Reader\ProductOfferStockReaderInterface;

class ReservationRequestExpander implements ReservationRequestExpanderInterface
{
    public function __construct(protected ProductOfferStockReaderInterface $productOfferStockReader)
    {
    }

    public function expandWithStores(ReservationRequestTransfer $reservationRequestTransfer): ReservationRequestTransfer
    {
        $productOfferReference = $reservationRequestTransfer->getProductOfferReference();
        if (!$productOfferReference) {
            return $reservationRequestTransfer;
        }

        $productOfferStockTransfers = $this->productOfferStockReader->getProductOfferStocks(
            (new ProductOfferStockRequestTransfer())
                ->setProductOfferReference($productOfferReference)
                ->setStore($reservationRequestTransfer->getStoreOrFail())
                ->setWithStores(true),
        );

        $existingStoreNames = $this->extractStoreNames($reservationRequestTransfer);
        foreach ($productOfferStockTransfers as $productOfferStockTransfer) {
            $storeRelationTransfer = $productOfferStockTransfer->getStock()?->getStoreRelation();
            if ($storeRelationTransfer === null) {
                continue;
            }

            $existingStoreNames = $this->addUniqueStores(
                $reservationRequestTransfer,
                $storeRelationTransfer,
                $existingStoreNames,
            );
        }

        return $reservationRequestTransfer;
    }

    /**
     * @return array<string, string>
     */
    protected function extractStoreNames(ReservationRequestTransfer $reservationRequestTransfer): array
    {
        $existingStoreNames = [];
        foreach ($reservationRequestTransfer->getStores() as $existingStoreTransfer) {
            $existingStoreName = $existingStoreTransfer->getName();
            if ($existingStoreName === null) {
                continue;
            }
            $existingStoreNames[$existingStoreName] = $existingStoreName;
        }

        return $existingStoreNames;
    }

    /**
     * @param array<string, string> $existingStoreNames
     *
     * @return array<string, string>
     */
    protected function addUniqueStores(
        ReservationRequestTransfer $reservationRequestTransfer,
        StoreRelationTransfer $storeRelationTransfer,
        array $existingStoreNames
    ): array {
        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeName = $storeTransfer->getName();
            if ($storeName === null || isset($existingStoreNames[$storeName])) {
                continue;
            }

            $reservationRequestTransfer->addStore($storeTransfer);
            $existingStoreNames[$storeName] = $storeName;
        }

        return $existingStoreNames;
    }
}
