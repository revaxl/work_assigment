<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Repositories\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('components.user.mypage', [
            'user' => $this->userRepository->getById(Auth::id())
        ]);
    }

    /**
     * @param UserUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(UserUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        try {
            throw_unless($this->userRepository->updateById(Auth::id(), $data), \Exception::class);

            return redirect()->route('user.index')->with(['message' => 'updated']);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());

            return redirect()->route('user.index')->withErrors(['error' => 'cannot update']);
        }
    }
}
