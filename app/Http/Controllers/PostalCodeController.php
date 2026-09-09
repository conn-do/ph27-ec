<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class PostalCodeController extends Controller
{
    public function __invoke(string $postalCode): JsonResponse
    {
        $normalizedPostalCode = preg_replace('/\D/', '', $postalCode);

        if (! is_string($normalizedPostalCode) || preg_match('/\A\d{7}\z/', $normalizedPostalCode) !== 1) {
            return response()->json([
                'message' => '郵便番号は7桁で入力してください。',
            ], 422);
        }

        try {
            $response = Http::acceptJson()
                ->connectTimeout(2)
                ->timeout(5)
                ->retry([100, 250], throw: false)
                ->get((string) config('services.zipcloud.url'), [
                    'zipcode' => $normalizedPostalCode,
                    'limit' => 1,
                ]);
        } catch (ConnectionException) {
            return response()->json([
                'message' => '住所を取得できませんでした。手動で入力してください。',
            ], 503);
        }

        if ($response->failed()) {
            return response()->json([
                'message' => '住所を取得できませんでした。手動で入力してください。',
            ], 503);
        }

        $result = $response->json('results.0');

        if (! is_array($result)) {
            return response()->json([
                'message' => '該当する住所が見つかりませんでした。',
            ], 404);
        }

        $address = collect(['address1', 'address2', 'address3'])
            ->map(fn (string $key): string => is_string($result[$key] ?? null) ? $result[$key] : '')
            ->implode('');

        return response()->json([
            'address' => $address,
        ]);
    }
}
