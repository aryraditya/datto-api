<?php

namespace App\Http\Controllers;

use App\Exports\DevicesExport;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page       = $request->get('page', 1);
        $client     = new Client([
            'base_uri'  => 'https://api.datto.com/v1/',
            'headers'   => [
                'Authorization' => 'Basic '. base64_encode(env('DATTO_USERNAME') . ':' . env('DATTO_KEY') ),
            ]
        ]);

        $response   = $client->get('bcdr/device', [
            'query'  => [
                '_page'      => $page
            ]
        ]);

        $data       = json_decode($response->getBody()->getContents());
        
        return view('home', [
            'data'  => $data,
            'page'  => $page,
        ]);
    }

    public function export()
    {
        return \Excel::download(new DevicesExport(), 'devices.xlsx');
    }
}
