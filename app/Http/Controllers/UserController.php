<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesJsonExceptions;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    use HandlesJsonExceptions;

    public function __construct(private readonly UserService $service) {}

    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return ApiResponse::success('Data pengguna.', User::query()->select(['id', 'name', 'username', 'email', 'role', 'is_active'])->latest('id')->get());
        }

        return view('users.index');
    }

    public function show(User $user): JsonResponse
    {
        return ApiResponse::success('Detail pengguna.', $user->only(['id', 'name', 'username', 'email', 'role', 'is_active']));
    }

    public function store(UserRequest $request): JsonResponse
    {
        try {
            return ApiResponse::created('Pengguna berhasil ditambahkan.', $this->service->create($request->validated()));
        } catch (Throwable $exception) {
            return $this->jsonFailure($exception);
        }
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        try {
            return ApiResponse::success('Pengguna berhasil diperbarui.', $this->service->update($user, $request->validated()));
        } catch (Throwable $exception) {
            return $this->jsonFailure($exception);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            $this->service->delete($user, request()->user()->id);

            return ApiResponse::success('Pengguna berhasil dihapus.');
        } catch (Throwable $exception) {
            return $this->jsonFailure($exception);
        }
    }
}
