<?php

namespace Tests\Unit;

use App\Models\Driver;
use App\Models\DriverReview;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverReviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_driver_review_belongs_to_driver(): void
    {
        $review = DriverReview::factory()->create();

        $this->assertInstanceOf(Driver::class, $review->driver);
    }
}
