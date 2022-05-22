<?php

namespace Modules\Otp\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Modules\Otp\Entities\Otp;
use Modules\Seo\Http\Traits\HasSeo;
use Modules\User\Entities\User;

class AuthenticateController extends Controller
{
    #region Traits

    use HasSeo;

    #endregion

    #region Pages

    /**
     * Show login page to unauthenticated user.
     *
     * @return Application|Factory|View
     */
    public function loginPage()
    {
        $this->initSeo('otp', 'login')->_generateSeoPage();
        return view(Lang::get('otp::page.login.view'));
    }

    /**
     * Show confirm page to unauthenticated user.
     *
     * @return Application|Factory|View
     */
    public function confirmPage()
    {
        $this->initSeo('otp', 'confirm')->_generateSeoPage();
        Session::reflash();
        return view(Lang::get('otp::page.confirm.view'));
    }

    #endregion

    #region Actions

    /**
     * Receive a phone number and generate new Otp.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone_number' => ['required',]
        ]);
        $otp = Otp::new()->requestCode($request->get('phone_number'));
        return redirect()->route('otp.authenticate.page.confirm.get.web')->with(['phone_number' => $otp->phone_number]);
    }

    /**
     * Receive a phone number and otp code then validation data
     * If data was valid create a user and login it otherwise shows error to user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function confirmOtp(Request $request)
    {
        Session::reflash();
        $request->validate([
            'phone_number' => ['required', 'exists:' . Otp::class . ',phone_number'],
            'otp_code' => ['required', 'numeric', 'digits:' . config('otp.random_code_length')],
        ]);
        $otp = Otp::query()->where(['phone_number' => $request->get('phone_number')])->first();
        if ($otp) {
            if ($otp->code == $request->get('otp_code')) {
                /** @var User $user */
                $user = User::query()->firstOrCreate(['phone_number' => $otp->phone_number], ['phone_number' => $otp->phone_number]);
                $otp->delete();
                Auth::login($user);
                return redirect('/');
            }
            return redirect()->back()->withErrors(['otp_code' => "code.invalid"]);
        }
        return redirect()->back()->withErrors(['otp_code' => "request invalid"]);
    }

    #endregion
}
