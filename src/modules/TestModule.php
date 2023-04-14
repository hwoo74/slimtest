<?php

namespace App\Modules;

class TestModule {

    private $varname = 'Willy';

    public function __construct( $name = null ) {
        if ( $name ) {
            $this->varname = $name;
        }
    }

    public function getWilly() : string
    {
        return $this->varname;
    }

}