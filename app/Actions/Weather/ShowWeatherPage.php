<?php

namespace App\Actions\Weather;

use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowWeatherPage
{
    use AsAction;

    public function handle(): Response
    {
        return Inertia::render('Weather/Index');
    }

    public function asController(): Response
    {
        return $this->handle();
    }
}
