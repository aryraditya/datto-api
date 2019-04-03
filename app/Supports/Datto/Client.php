<?php


namespace App\Supports\Datto;



use GuzzleHttp\Exception\RequestException;

class Client
{
    /**
     * @var Datto
     */
    private $datto;

    public function __construct($datto)
    {
        $this->datto    = $datto;
    }

    public function get($url, $params = [])
    {
        try {
            $response =  $this->client()->request('get', $url, [
                'query'    => $params
            ]);

            return json_decode($response->getBody()->getContents());
        } catch (RequestException $ex) {
            return null;
        }
    }

    public function client()
    {
        return new \GuzzleHttp\Client([
            'base_uri'  => 'https://api.datto.com/v1/',
            'headers'   => [
                'Authorization' => 'Basic '. base64_encode( $this->datto->username() . ':' . $this->datto->key() ),
            ]
        ]);
    }
}