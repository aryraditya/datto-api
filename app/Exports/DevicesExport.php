<?php

namespace App\Exports;

use GuzzleHttp\Client;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DevicesExport implements FromView
{
    public function view(): View
    {
        $client     = new Client([
            'base_uri'  => 'https://api.datto.com/v1/',
            'headers'   => [
                'Authorization' => 'Basic '. base64_encode(env('DATTO_USERNAME') . ':' . env('DATTO_SECRET') ),
            ]
        ]);

        $response   = $client->get('bcdr/device', [
            'query_params'  => [
            ]
        ]);

        $data       = json_decode($response->getBody()->getContents());

        return view('exports.devices', compact('data'));
    }
}
