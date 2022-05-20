<?php

namespace Modules\Otp\Tests\Feature\Model;

use Modules\Otp\Entities\Otp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OtpModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Check code is expired after specified period.
     *
     * @return void
     */
    public function testCodeIsExpiredAfterExpirationEnds()
    {
        $phone_number = $this->faker->numerify('+989#########');
        $this->assertDatabaseCount(Otp::class, 0)
            ->assertDatabaseMissing(Otp::class, ['phone_number' => $phone_number]);
        $otp = Otp::new()->requestCode($phone_number);
        $this->assertDatabaseCount(Otp::class, 1)
            ->assertDatabaseHas(Otp::class, ['phone_number' => $otp->getAttribute('phone_number'), 'code' => $otp->getAttribute('code')]);
        $this->travel(config('otp.expiration_period') + 10)->seconds(function () use ($phone_number, $otp) {
            $new_otp = Otp::new()->requestCode($phone_number);
            $this->assertDatabaseMissing(Otp::class, ['phone_number' => $otp->getAttribute('phone_number'), 'code' => $otp->getAttribute('code')])
                ->assertDatabaseHas(Otp::class, ['phone_number' => $new_otp->getAttribute('phone_number'), 'code' => $new_otp->getAttribute('code')]);
            $this->assertNotEquals($otp->only(['phone_number', 'code']), $new_otp->only(['phone_number', 'code']));
        });
    }

    /**
     * Check code is not expired before specified period.
     *
     * @return void
     */
    public function testCodeIsNotExpiredBeforeExpirationEnds()
    {
        $phone_number = $this->faker->numerify('+989#########');
        $this->assertDatabaseCount(Otp::class, 0)
            ->assertDatabaseMissing(Otp::class, ['phone_number' => $phone_number]);
        $otp = Otp::new()->requestCode($phone_number);
        $this->assertDatabaseCount(Otp::class, 1)
            ->assertDatabaseHas(Otp::class, ['phone_number' => $otp->getAttribute('phone_number'), 'code' => $otp->getAttribute('code')]);
        $this->travel(config('otp.expiration_period') - 10)->seconds(function () use ($phone_number, $otp) {
            $new_otp = Otp::new()->requestCode($phone_number);
            $this->assertDatabaseHas(Otp::class, ['phone_number' => $otp->getAttribute('phone_number'), 'code' => $otp->getAttribute('code')])
                ->assertDatabaseHas(Otp::class, ['phone_number' => $new_otp->getAttribute('phone_number'), 'code' => $new_otp->getAttribute('code')]);
            $this->assertEquals($otp->only(['phone_number', 'code']), $new_otp->only(['phone_number', 'code']));
        });
    }

    /**
     * Check the results of new static method in model.
     *
     * @return void
     */
    public function testNewStaticFunctionResult()
    {
        $this->assertInstanceOf(Otp::class, Otp::new());
    }

}
