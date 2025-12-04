<?php

namespace App\Actions\Weather;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class GetWeatherData
{
    use AsAction;

    public function handle(string $city): array
    {
        $apiKey = env('OPENWEATHER_API_KEY', 'demo');

        if ($apiKey === 'demo' || $apiKey === null) {
            return [
                'success' => true,
                'data' => [
                    'name' => ucfirst(strtolower($city)),
                    'sys' => ['country' => 'XX'],
                    'weather' => [
                        ['description' => 'clear sky', 'icon' => '01d']
                    ],
                    'main' => [
                        'temp' => 20,
                        'feels_like' => 18,
                        'temp_min' => 15,
                        'temp_max' => 25,
                        'humidity' => 65,
                        'pressure' => 1013
                    ],
                    'wind' => [
                        'speed' => 5.5
                    ]
                ],
            ];
        }

        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        $errorData = $response->json();
        $errorMessage = 'City not found';

        if (isset($errorData['message'])) {
            if ($response->status() === 401) {
                $errorMessage = 'Invalid API key. Please get a valid key from openweathermap.org';
            } else if ($response->status() === 404) {
                $errorMessage = 'City not found. Please try another city name.';
            } else {
                $errorMessage = $errorData['message'];
            }
        }

        return [
            'success' => false,
            'message' => $errorMessage,
        ];
    }

    public function asController(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        try {
            $result = $this->handle($request->input('city'));
            
            return Inertia::render('Weather/Index', $result);
        } catch (\Exception $e) {
            return Inertia::render('Weather/Index', [
                'success' => false,
                'message' => 'Error fetching weather data',
            ]);
        }
    }
}
