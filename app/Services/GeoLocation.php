<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoLocation
{
    /**
     * Detect the visitor's country from their IP address.
     * Caches result in session to avoid repeated API calls.
     *
     * @return array{country: string|null, city: string|null, countryCode: string|null}
     */
    public function detect(): array
    {
        $cached = session('geo_location');
        if ($cached) {
            return $cached;
        }

        $result = [
            'country' => null,
            'city' => null,
            'countryCode' => null,
        ];

        try {
            $ip = request()->ip();

            // Skip detection for localhost/private IPs
            if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
                // Default to Pakistan for local development
                $result = [
                    'country' => 'Pakistan',
                    'city' => null,
                    'countryCode' => 'PK',
                ];
                session(['geo_location' => $result]);

                return $result;
            }

            // Try Cloudflare header first (fastest, no API call)
            $cfCountry = request()->header('CF-IPCountry');
            if ($cfCountry && $cfCountry !== 'XX') {
                $result['countryCode'] = $cfCountry;
                $result['country'] = $this->countryCodeToName($cfCountry);
                session(['geo_location' => $result]);

                return $result;
            }

            // Fall back to ip-api.com (free, 45 req/min)
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}", [
                'fields' => 'status,country,city,countryCode',
            ]);

            if ($response->successful() && $response->json('status') === 'success') {
                $result = [
                    'country' => $response->json('country'),
                    'city' => $response->json('city'),
                    'countryCode' => $response->json('countryCode'),
                ];
            }
        } catch (\Throwable $e) {
            Log::debug('GeoLocation detection failed: ' . $e->getMessage());
        }

        session(['geo_location' => $result]);

        return $result;
    }

    /**
     * Get the detected country name, or null if not detected.
     */
    public function country(): ?string
    {
        return $this->detect()['country'];
    }

    /**
     * Convert ISO country code to country name for common cases.
     */
    private function countryCodeToName(string $code): string
    {
        $map = [
            'PK' => 'Pakistan',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'AE' => 'United Arab Emirates',
            'SA' => 'Saudi Arabia',
            'IN' => 'India',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'TR' => 'Turkey',
            'BD' => 'Bangladesh',
        ];

        return $map[strtoupper($code)] ?? $code;
    }
}
