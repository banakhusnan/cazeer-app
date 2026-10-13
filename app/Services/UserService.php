<?php

namespace App\Services;

use App\Models\User;
use App\Services\Concerns\HandlesServiceErrors;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class UserService
{
    use HandlesServiceErrors;

    public function create(array $data): User
    {
        return $this->execute('user.create', fn () => User::query()->create($data));
    }

    public function update(User $user, array $data): User
    {
        return $this->execute('user.update', function () use ($user, $data) {
            if (blank($data['password'] ?? null)) {
                $data = Arr::except($data, 'password');
            }

            $user->update($data);

            return $user->refresh();
        });
    }

    public function delete(User $user, int $actorId): void
    {
        $this->execute('user.delete', function () use ($user, $actorId) {
            if ($user->id === $actorId) {
                throw ValidationException::withMessages(['user' => 'Akun yang sedang digunakan tidak dapat dihapus.']);
            }

            $user->delete();
        });
    }
}
