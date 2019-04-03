<?php


namespace App\Supports\Datto;


class Service
{
    /**
     * @var Datto
     */
    protected $datto;

    public function __construct(Datto $datto = null)
    {
        $this->datto = $datto ?: new Datto();
    }
}