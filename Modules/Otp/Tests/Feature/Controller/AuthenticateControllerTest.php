<?php

namespace Modules\Otp\Tests\Feature\Controller;

use Modules\Otp\Entities\Otp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticateControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testRequestOtpPostWeb()
    {
        $phone_number = $this->faker->numerify('+989#########');
        $this->assertDatabaseCount(Otp::class, 0)
            ->assertDatabaseMissing(Otp::class, ['phone_number' => $phone_number])
            ->post(route('otp.authenticate.request-otp.post.web'), ['phone_number' => $phone_number])
            ->assertRedirect(route('otp.authenticate.page.confirm.get.web'));
        $this->assertDatabaseCount(Otp::class, 1)
            ->assertDatabaseHas(Otp::class, ['phone_number' => $phone_number]);
    }
}
