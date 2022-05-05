<?php

namespace Brixion\Bardecoder;

class UdiDecoder
{
    public function __construct()
    {

    }

    public function decode(string $barcode): UdiDecoder
    {
        $decoder = new UdiDecoder();
        
        return $decoder;
    }

}