<?php

namespace Modules\Otp\Tests\Feature\Database;

use Modules\Otp\Entities\Otp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OtpDatabaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Otp data creation in database.
     *
     * @return void
     */
    public function testInsertOtpData()
    {
        $otp = Otp::factory()->create();
        $this->assertDatabaseHas(Otp::class, $otp->getAttributes());
    }
}
