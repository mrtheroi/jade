<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class ExtractService
{
    public function extraction(UploadedFile $file): Response
    {
        $url = config('services.extract.webhook_url');

        return Http::timeout(config('services.extract.timeout', 60))
            ->retry(2, 300) // 2 reintentos con backoff 300ms
            ->acceptJson()
            ->attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )
            ->post($url);
    }

}
