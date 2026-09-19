<?php

namespace App\Helpers;

use App\Models\Cool;
use App\Models\QrAccess;
use App\Models\RefSetting;
use Illuminate\Support\Facades\Auth;

class AppHelper
{
    public static function isShepherdOrAdmin(): bool
    {
        $user = Auth::user();
        return $user && $user->role && in_array($user->role->name, ['SHEPHERD', 'ADMIN', 'SUPERADMIN']);
    }

    public static function getShepherdId(): ?int
    {
        $user = Auth::user();
        return $user && $user->role && $user->role->name === 'SHEPHERD' ? $user->shepherd_id : null;
    }

    public static function getCoolIdsForShepherd(int $shepherdId): array
    {
        return Cool::where('shepherd_id', $shepherdId)
            ->where('is_deleted', false)
            ->pluck('cool_id')
            ->toArray();
    }

    public static function canAccessQr(int $qrAccessId): bool
    {
        $user = Auth::user();
        $qr = QrAccess::with('cool')->findOrFail($qrAccessId);
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        return !$isShepherd || ($isShepherd && $qr->cool && $qr->cool->shepherd_id === $user->shepherd_id);
    }

    public static function getSettings(string $name)
    {
        $setting = RefSetting::where('name', $name)->first();

        if ($setting->type === 'boolean') {
            $setting->value = (bool) $setting->value;
        } elseif ($setting->type === 'number') {
            $setting->value = (int) $setting->value;
        } elseif ($setting->type === 'date') {
            $setting->value = (int) $setting->value;
        } elseif ($setting->type === 'string') {
            $setting->value = (string) $setting->value;
        } elseif ($setting->type === 'integer') {
            $setting->value = (int) $setting->value;
        } else {
            $setting->value = $setting->value;
        }

        return $setting->value;
    }
}