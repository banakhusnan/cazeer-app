<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesJsonExceptions;
use App\Http\Requests\StoreSettingRequest;
use App\Models\StoreSetting;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class StoreSettingController extends Controller
{
    use HandlesJsonExceptions;

    public function edit(): View
    {
        return view('settings.index', ['setting' => StoreSetting::query()->first()]);
    }

    public function update(StoreSettingRequest $request): JsonResponse
    {
        try {
            $data = $request->safe()->except('logo');
            $setting = StoreSetting::query()->firstOrNew();
            if ($request->hasFile('logo')) {
                if ($setting->logo_path) {
                    Storage::disk('public')->delete($setting->logo_path);
                }
                $data['logo_path'] = $request->file('logo')->store('logos', 'public');
            }
            $setting->fill($data)->save();

            return ApiResponse::success('Pengaturan toko berhasil disimpan.', $setting);
        } catch (Throwable $exception) {
            Log::error('Pengaturan toko gagal disimpan.', ['user_id' => $request->user()->id, 'exception' => $exception]);

            return $this->jsonFailure($exception);
        }
    }
}
