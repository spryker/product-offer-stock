<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Communication\Plugin\Oms;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationRequestExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductOfferStock\ProductOfferStockConfig getConfig()
 */
class ProductOfferStockReservationRequestExpanderPlugin extends AbstractPlugin implements ReservationRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Lists every store that carries product-offer stock for the requested product offer
     *   and appends each one to the reservation request, deduplicated by store name.
     * - No-op when `ReservationRequestTransfer.productOfferReference` is not set.
     * - Pre-existing entries in `ReservationRequestTransfer.stores` are kept.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationRequestTransfer
     */
    public function expand(ReservationRequestTransfer $reservationRequestTransfer): ReservationRequestTransfer
    {
        return $this->getBusinessFactory()->createReservationRequestExpander()->expandWithStores($reservationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns true when `ReservationRequestTransfer.productOfferReference` is set, so the
     *   plugin only runs for product-offer reservations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ReservationRequestTransfer $reservationRequestTransfer): bool
    {
        return (bool)$reservationRequestTransfer->getProductOfferReference();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 200;
    }
}
