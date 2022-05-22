<?php

namespace Modules\Otp\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Http\Responses\Api;
use Modules\Otp\Entities\Otp;
use Modules\User\Entities\User;

class ApiAuthenticateController extends Controller
{
    /**
     * Receive a phone number and generate new Otp.
     *
     * @param Request $request
     * @return void
     */
    public function requestOtp(Request $request)
    {
        Api::check($request->all(), [
            'phone_number' => ['required',]
        ]);
        $otp = Otp::new()->requestCode($request->get('phone_number'));
        Api::message(Lang::get('otp::api.responses.code_generated'))
            ->setData($otp->only(['phone_number']))
            ->send();
    }

    /**
     * Confirm Otp and generate token for user.
     *
     * @param Request $request
     * @return void
     */
    public function confirmOtp(Request $request)
    {
        Api::check($request->all(), [
            'phone_number' => ['required',],
            'otp_code' => ['required', 'numeric', 'digits:' . config('otp.random_code_length')],
        ]);
        $otp = Otp::query()->where(['phone_number' => $request->get('phone_number')])->first();
        if ($otp) {
            if ($otp->code == $request->get('otp_code')) {
                /** @var User $user */
                $user = User::query()->firstOrCreate(['phone_number' => $otp->phone_number], ['phone_number' => $otp->phone_number]);
                $otp->delete();
                $token = $user->createToken('rest-api-token')->plainTextToken;
                Api::message(Lang::get('otp::api.responses.' . ($user->wasRecentlyCreated ? 'login_succeed_and_user_created' : 'login_succeed')))
                    ->addData('token', $token)
                    ->addData('user', $user)
                    ->send();
            }
            Api::message(Lang::get('otp::api.responses.code_invalid'))
                ->failedWithError()
                ->send();
        }
        Api::message(Lang::get('otp::api.responses.no_request_founded'))
            ->failedWithError()
            ->send();
    }
}
