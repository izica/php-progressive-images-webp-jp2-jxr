<?php

namespace Izica;

class ProgressiveImagesResponse
{
    public $source = null;
    public $webp = null;
    public $jpeg2000 = null;
    public $jpegxr = null;

    function __construct($source, $webp, $jpeg2000, $jpegxr)
    {
        $this->source = $source;
        $this->webp = $webp;
        $this->jpeg2000 = $jpeg2000;
        $this->jpegxr = $jpegxr;
    }

    public function toArray()
    {
        return [
            'source'   => $this->source,
            'webp'     => $this->webp,
            'jpeg2000' => $this->jpeg2000,
            'jpegxr'   => $this->jpegxr,
        ];
    }

}