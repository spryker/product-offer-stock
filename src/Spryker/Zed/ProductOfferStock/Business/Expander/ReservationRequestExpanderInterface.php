<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business\Expander;

use Generated\Shared\Transfer\ReservationRequestTransfer;

interface ReservationRequestExpanderInterface
{
    public function expandWithStores(ReservationRequestTransfer $reservationRequestTransfer): ReservationRequestTransfer;
}
