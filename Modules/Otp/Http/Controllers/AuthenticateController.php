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
use Illuminate\Support\Facades\Session;
use Modules\Otp\Entities\Otp;

class AuthenticateController extends Controller
{
    #region Pages

    /**
     * Show login form to unauthenticated user.
     *
     * @return Application|Factory|View
     */
    public function loginPage()
    {
        SEOTools::setTitle("salam")
            ->setDescription("des")
            ->twitter()
            ->setTitle("");
        return view('otp::login');
    }

    /**
     * Show confirm form to unauthenticated user.
     *
     * @return Application|Factory|View
     */
    public function confirmPage()
    {
        SEOTools::setTitle("salam")
            ->setDescription("des")
            ->twitter()
            ->setTitle("");
        Session::reflash();
        return view('otp::confirm');
    }

    #endregion

    #region Actions

    /**
     * Receive a phone number and generate a unique random code.
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
                dd("Hello user :)");
            } else {
                return redirect()->back()->withErrors(['otp_code' => "code.invalid"]);
            }
        }
        return redirect()->back()->withErrors(['otp_code' => "phone number not exists"]);
    }

    #endregion
}
