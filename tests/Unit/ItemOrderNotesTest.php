<?php

namespace Tests\Unit;

use App\Models\ItemOrder;
use PHPUnit\Framework\TestCase;

class ItemOrderNotesTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     * 1 = Unfulfilled
     * 2 = Processing
     * 3 = Fulfilled
     * 4 = Cancelled
     */
    public function testUnfulfilledOrderItems()
    {
        $response = ItemOrder::amendOrderStatus([1, 1, 1, 1]);
        $this->assertTrue($response == 1);
    }

    public function testProcessingOrderItems()
    {
        $response = ItemOrder::amendOrderStatus([2, 2, 2, 2]);
        $this->assertTrue($response == 2);
    }

    public function testFulfilledOrderItems()
    {
        $response = ItemOrder::amendOrderStatus([3, 3, 3, 3]);
        $this->assertTrue($response == 3);
    }

    public function testCancelledOrderItems()
    {
        $response = ItemOrder::amendOrderStatus([4, 4, 4, 4]);
        $this->assertTrue($response == 4);
    }

    public function testOrderItemContainsUnfulfilled()
    {
        $response = ItemOrder::amendOrderStatus([3, 1, 1, 1]);
        $this->assertTrue($response == 1);
    }

    public function testOrderItemContainsAProcessingOrder()
    {
        $response = ItemOrder::amendOrderStatus([2, 3, 4, 3]);
        $this->assertTrue($response == 2);
    }

    public function testOrderItemContainsAFulfilledOrder()
    {
        $response = ItemOrder::amendOrderStatus([4, 3, 3, 3]);
        $this->assertTrue($response == 3);
    }

    public function testOrderItemContainsAFulfilledOrderAndProcessingOrder()
    {
        $response = ItemOrder::amendOrderStatus([4, 4, 4, 4]);
        $this->assertTrue($response == 4);
    }
}
