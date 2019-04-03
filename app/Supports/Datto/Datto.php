<?php


namespace App\Supports\Datto;


use App\Supports\Datto\Services\Device;

class Datto
{
    /**
     * @var Client
     */
    private $_http;

    public function __construct()
    {
        $this->_http = new Client($this);
    }

    public function username()
    {
        return config('services.datto.username');
    }

    public function key()
    {
        return config('services.datto.key');
    }

    public function http()
    {
        return $this->_http;
    }

    /**
     * @return Device
     */
    public function device()
    {
        return new Device($this);
    }
}