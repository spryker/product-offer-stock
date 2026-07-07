<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStock\Business\Plugin\Oms;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOfferStock\Communication\Plugin\Oms\ProductOfferStockReservationRequestExpanderPlugin;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferStock
 * @group Business
 * @group Plugin
 * @group Oms
 * @group ProductOfferStockReservationRequestExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductOfferStockReservationRequestExpanderPluginTest extends Unit
{
    use DataCleanupHelperTrait;

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\ProductOfferStock\ProductOfferStockBusinessTester
     */
    protected $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductOfferStockTableIsEmpty();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->getDataCleanupHelper()->_addCleanup(function (): void {
            $this->tester->ensureProductOfferStockTableIsEmpty();
        });
    }

    public function testExpandReturnsRequestUnchangedWhenProductOfferReferenceIsNull(): void
    {
        // Arrange
        $reservationRequestTransfer = new ReservationRequestTransfer();

        // Act
        $resultReservationRequestTransfer = $this->createPlugin()->expand($reservationRequestTransfer);

        // Assert
        $this->assertSame($reservationRequestTransfer, $resultReservationRequestTransfer);
        $this->assertCount(0, $resultReservationRequestTransfer->getStores());
    }

    public function testExpandReturnsRequestUnchangedWhenProductOfferReferenceIsEmptyString(): void
    {
        // Arrange
        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setProductOfferReference('');

        // Act
        $resultReservationRequestTransfer = $this->createPlugin()->expand($reservationRequestTransfer);

        // Assert
        $this->assertCount(0, $resultReservationRequestTransfer->getStores());
    }

    public function testExpandAppendsStoreAssignedToProductOfferStock(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $stockTransfer = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer->toArray(),
        ]);

        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setStore($storeTransfer);

        // Act
        $resultReservationRequestTransfer = $this->createPlugin()->expand($reservationRequestTransfer);

        // Assert
        $this->assertCount(1, $resultReservationRequestTransfer->getStores());
        $this->assertSame(
            $storeTransfer->getName(),
            $resultReservationRequestTransfer->getStores()->getIterator()->current()->getName(),
        );
    }

    public function testExpandAppendsAllStoresWhenStockIsAssignedToMultipleStores(): void
    {
        // Arrange
        $primaryStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $secondaryStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $stockTransfer = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_STORES => [
                    $primaryStoreTransfer->getIdStore(),
                    $secondaryStoreTransfer->getIdStore(),
                ],
            ],
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer->toArray(),
        ]);

        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setStore($primaryStoreTransfer);

        // Act
        $resultReservationRequestTransfer = $this->createPlugin()->expand($reservationRequestTransfer);

        // Assert
        $resultStoreNames = $this->collectStoreNames($resultReservationRequestTransfer);
        $this->assertCount(2, $resultStoreNames);
        $this->assertContains($primaryStoreTransfer->getName(), $resultStoreNames);
        $this->assertContains($secondaryStoreTransfer->getName(), $resultStoreNames);
    }

    public function testExpandDoesNotDuplicateStoresAlreadyPresentOnTheRequest(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $stockTransfer = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer->toArray(),
        ]);

        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setStore($storeTransfer)
            ->addStore((new StoreTransfer())->setName($storeTransfer->getName()));

        // Act
        $resultReservationRequestTransfer = $this->createPlugin()->expand($reservationRequestTransfer);

        // Assert
        $this->assertCount(1, $resultReservationRequestTransfer->getStores());
    }

    public function testIsApplicableReturnsTrueWhenProductOfferReferenceIsSet(): void
    {
        // Arrange
        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setProductOfferReference('any-ref');

        // Act + Assert
        $this->assertTrue($this->createPlugin()->isApplicable($reservationRequestTransfer));
    }

    public function testIsApplicableReturnsFalseWhenProductOfferReferenceIsNull(): void
    {
        // Arrange
        $reservationRequestTransfer = new ReservationRequestTransfer();

        // Act + Assert
        $this->assertFalse($this->createPlugin()->isApplicable($reservationRequestTransfer));
    }

    public function testIsApplicableReturnsFalseWhenProductOfferReferenceIsEmptyString(): void
    {
        // Arrange
        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setProductOfferReference('');

        // Act + Assert
        $this->assertFalse($this->createPlugin()->isApplicable($reservationRequestTransfer));
    }

    /**
     * @return array<string>
     */
    protected function collectStoreNames(ReservationRequestTransfer $reservationRequestTransfer): array
    {
        $storeNames = [];
        foreach ($reservationRequestTransfer->getStores() as $storeTransfer) {
            $storeNames[] = $storeTransfer->getName();
        }

        return $storeNames;
    }

    protected function createPlugin(): ProductOfferStockReservationRequestExpanderPlugin
    {
        return new ProductOfferStockReservationRequestExpanderPlugin();
    }
}
