<?php

namespace App\Http\Controllers;

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
                'Authorization' => 'Basic '.env('DATTO_TOKEN'),
            ]
        ]);

        $response   = $client->get('bcdr/device', [
            'query_params'  => [
                'page'      => $page
            ]
        ]);

        $data       = json_decode($response->getBody()->getContents());
        
        return view('home', [
            'data'  => $data,
            'page'  => $page,
        ]);
    }
}
