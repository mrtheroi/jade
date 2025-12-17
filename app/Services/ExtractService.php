<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class ExtractService
{
    public function extraction(UploadedFile $file): Response
    {
        return Http::attach(
            'file', // nombre del campo que recibe el webhook
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post(
            'https://misxv.ccdesarrollo.site/webhook/7e2b8bcf-ce9a-4c13-b04d-cdbe4e1b3f71');
    }

}
