<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
/***/
use App\Http\Controllers\AbstractController;
use App\Models\User;

abstract class AbstractForgotPasswordController extends AbstractController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot_password', [
            'loginUri' => $this->getLoginUri(),
        ]);
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request) : void
    {
        $request->validate([
            'email' => [
                'bail', 
                'required', 
                'email', 
                'exists:' . implode(',', [ User::class, 'email', ]), 
            ],
        ]);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        if($request->wantsJson())
        {
            return response()->json(
                data: [
                    'message' => trans($response),
                ],
                status: 200
            );
        }

        return redirect($this->getSuccessUri())->with('success', trans($response));
    }

    /**
     * Retourne l'URI de redirection en cas de succès
     * @return string
     */
    abstract protected function getSuccessUri() : string;

    /**
     * Retourne l'URI de connexion
     * @return string
     */
    abstract protected function getLoginUri() : string;

}
