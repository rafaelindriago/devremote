<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UserImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke()
    {
        /**
         * @var \App\Models\User
         */
        $user = Auth::user();

        $imagePath = "images/users/{$user->id}";

        if (Storage::has($imagePath)) {
            return response()
                ->stream(function () use ($imagePath): void {
                    echo Storage::get($imagePath);
                }, 200, [
                    'Content-Type' => Storage::mimeType($imagePath),
                ]);
        }

        $cacheKey = "users.images.{$user->id}";

        return response()
            ->stream(function () use ($user, $cacheKey): void {
                echo Cache::get($cacheKey, function () use ($user, $cacheKey) {

                    $svgContent = Http::retry(3, 1000)
                        ->get('https://ui-avatars.com/api', [
                            'name' => $user->name,
                            'length' => 1,
                            'color' => '0d6efd',
                            'background' => 'ffffff',
                            'format' => 'svg',
                        ])
                        ->body();

                    Cache::put($cacheKey, $svgContent);

                    return $svgContent;
                });
            }, 200, [
                'Content-Type' => 'image/svg+xml',
            ]);
    }
}
