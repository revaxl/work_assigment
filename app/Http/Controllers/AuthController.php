<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * @var UserRepository
     */
    private $UserRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->UserRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        // validate the form data
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        // attempt to login with the given credentials
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // if login is successful then redirect the user to mypage
            return redirect()->route('user.index');
        }

        // if login fail, return the previous page and show error messages
        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * @return View
     */
    public function registration(): View
    {
        return view('components.auth.registration');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        // validate the form data
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string']
        ]);

        try {
            // add the user to the users table
            $user = $this->UserRepository->create($data);
            throw_if(empty($user), \Exception::class);
            // login the new registered user
            Auth::login($user);

            // redirect the user to mypage
            return redirect()->route('user.index');
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());

            return redirect()->route('home')->withErrors(['error' => 'cannot register']);
        }
    }

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        // empty the session
        Session::flush();
        // logout the user
        Auth::logout();

        // redirect to the home page
        return redirect('/');
    }
}
