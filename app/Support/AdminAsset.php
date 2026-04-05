<?php

namespace App\Support;

use Illuminate\Http\Response;

class AdminAsset
{
    public static function css(): Response
    {
        return response(
            file_get_contents(resource_path('css/app.css')),
            200,
            [
                'Content-Type' => 'text/css; charset=UTF-8',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]
        );
    }

    public static function js(): Response
    {
        return response(
            file_get_contents(public_path('js/admin.js')),
            200,
            [
                'Content-Type' => 'application/javascript; charset=UTF-8',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]
        );
    }

    public static function version(string $path): string
    {
        return (string) filemtime($path);
    }
}
