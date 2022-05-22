<?php

namespace Modules\Otp\Tests\Feature\Controller;

use Modules\Otp\Entities\Otp;
use Modules\User\Entities\User;
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

    public function testRequestOtpPostWebFailedOnInvalidCode()
    {
        $phone_number = $this->faker->numerify('########');
        $this->get(route('otp.authenticate.page.login.get.web'))->assertOk();
        $this->assertDatabaseCount(Otp::class, 0)
            ->assertDatabaseMissing(Otp::class, ['phone_number' => $phone_number])
            ->post(route('otp.authenticate.request-otp.post.web'), ['phone_number' => $phone_number])
            ->assertRedirect(route('otp.authenticate.page.login.get.web'))
            ->assertInvalid(['phone_number']);
        $this->assertDatabaseCount(Otp::class, 0)
            ->assertDatabaseMissing(Otp::class, ['phone_number' => $phone_number]);
    }

    public function testRequestOtpPostWebSuccessOnValidCode()
    {
        $phone_number = $this->faker->numerify('+989#########');
        $this->assertDatabaseCount(Otp::class, 0)
            ->assertDatabaseMissing(Otp::class, ['phone_number' => $phone_number])
            ->post(route('otp.authenticate.request-otp.post.web'), ['phone_number' => $phone_number])
            ->assertRedirect(route('otp.authenticate.page.confirm.get.web'))
            ->assertSessionDoesntHaveErrors(['phone_number']);
        $this->assertDatabaseCount(Otp::class, 1)
            ->assertDatabaseHas(Otp::class, ['phone_number' => $phone_number]);
    }

    public function testConfirmOtpPostWebFailedOnInvalidCode()
    {
        $phone_number = $this->faker->numerify('+989#########');
        $this->post(route('otp.authenticate.request-otp.post.web'), ['phone_number' => $phone_number])
            ->assertRedirect(route('otp.authenticate.page.confirm.get.web'));
        $this->get(route('otp.authenticate.page.confirm.get.web'))->assertOk();
        $this->post(route('otp.authenticate.confirm-otp.post.web'), [
            'phone_number' => $phone_number,
            'otp_code' => $this->faker->randomNumber(config('otp.random_code_length')),
        ])->assertRedirect(route('otp.authenticate.page.confirm.get.web'))
            ->assertInvalid(['otp_code']);
        $this->assertDatabaseCount(Otp::class, 1)
            ->assertDatabaseHas(Otp::class, ['phone_number' => $phone_number]);
    }

    public function testConfirmOtpPostWebSuccessOnValidCode()
    {
        $phone_number = $this->faker->numerify('+989#########');
        $this->assertGuest('web')
            ->assertDatabaseCount(Otp::class, 0)
            ->assertDatabaseMissing(Otp::class, ['phone_number' => $phone_number])
            ->post(route('otp.authenticate.request-otp.post.web'), ['phone_number' => $phone_number])
            ->assertRedirect(route('otp.authenticate.page.confirm.get.web'));
        $this->assertDatabaseCount(Otp::class, 1)
            ->assertDatabaseHas(Otp::class, ['phone_number' => $phone_number]);
        $otp = Otp::query()->where(['phone_number' => $phone_number])->first();
        $this->get(route('otp.authenticate.page.confirm.get.web'))->assertOk();
        $this->post(route('otp.authenticate.confirm-otp.post.web'), [
            'phone_number' => $otp->phone_number,
            'otp_code' => $otp->code,
        ])->assertRedirect('/')->assertSessionDoesntHaveErrors(['phone_number', 'otp_code']);
        $this->assertDatabaseCount(Otp::class, 0)
            ->assertDatabaseCount(User::class, 1)
            ->assertDatabaseHas(User::class, ['phone_number' => $otp->phone_number])
            ->assertDatabaseMissing(Otp::class, $otp->getAttributes());
        $this->assertAuthenticated('web');
    }
}
