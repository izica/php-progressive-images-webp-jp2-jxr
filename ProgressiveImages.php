<?php

namespace Izica;

require_once 'ProgressiveImagesResponse.php';

class ProgressiveImages
{
    private $sFileSource = null;
    private $sFileName = null;
    private $sDestinationFolder = '';
    private $fWebp = true;
    private $fJpeg2000 = true;
    private $fJpegXr = true;

    function __construct($sFileSource)
    {
        $this->sFileSource = $sFileSource;
    }

    public static function fromFileSource($sFileSource)
    {
        return new ProgressiveImages($sFileSource);
    }

    public function setDestinationFolder($sDestinationFolder)
    {
        if (!is_dir($sDestinationFolder)) {
            if (!mkdir($sDestinationFolder, 0755, true)) {
                $this->responseError("Can't create destination folder");
            }
        }
        $this->sDestinationFolder = $sDestinationFolder;
        return $this;
    }

    public function setFileName($sFileName)
    {
        $this->sFileName = $sFileName;
        return $this;
    }

    public function withoutWebp()
    {
        $this->fWebp = false;
        return $this;
    }

    public function withoutJpegXr()
    {
        $this->fJpegXr = false;
        return $this;
    }

    public function withoutJpeg2000()
    {
        $this->fJpeg2000 = false;
        return $this;
    }

    public function convert()
    {
        $sFilepath = $this->getFilePath($this->sFileSource);

        if (!$sFilepath) {
            return $this->responseError('Source file not found');
        }

        $sFilename = $this->sFileName !== null ? $this->sFileName : $this->getFileName($sFilepath);

        return new ProgressiveImagesResponse(
            str_replace($_SERVER['DOCUMENT_ROOT'], '', $sFilepath),
            $this->toWebp($sFilepath, $sFilename),
            $this->toJpeg2000($sFilepath, $sFilename),
            $this->toJpegXr($sFilepath, $sFilename)
        );
    }

    private function toWebp($sSource, $sFilename)
    {
        $sDest = $this->sDestinationFolder . $sFilename . '.webp';
        $sDestWeb = str_replace($_SERVER['DOCUMENT_ROOT'], '', $sDest);

        if (file_exists($sDest)) {
            return $sDestWeb;
        }

        if (!$this->fWebp) {
            return null;
        }

        exec("convert {$sSource} {$sDest}");

        return $sDestWeb;
    }

    private function toJpegXr($sSource, $sFilename)
    {
        $sDestBuffer = $this->sDestinationFolder . $sFilename . '.tif';
        $sDest = $this->sDestinationFolder . $sFilename . '.jxr';
        $sDestWeb = str_replace($_SERVER['DOCUMENT_ROOT'], '', $sDest);

        if (file_exists($sDest)) {
            return $sDestWeb;
        }

        if (!$this->fJpegXr) {
            return null;
        }

        exec("convert {$sSource} -compress none {$sDestBuffer}");
        exec("JxrEncApp -i {$sDestBuffer} -o $sDest", $sResponse);
        exec("rm {$sDestBuffer}");

        return $sDestWeb;
    }

    private function toJpeg2000($sSource, $sFilename)
    {
        $sDest = $this->sDestinationFolder . $sFilename . '.jp2';
        $sDestWeb = str_replace($_SERVER['DOCUMENT_ROOT'], '', $sDest);

        if (file_exists($sDest)) {
            return $sDestWeb;
        }

        if (!$this->fJpeg2000) {
            return null;
        }

        exec("convert {$sSource} {$sDest}");

        return $sDestWeb;
    }

    private function getFileName($sFilePath)
    {
        $sBasename = basename($sFilePath);

        $arBasename = explode('.', $sBasename);
        if (count($arBasename) > 1) {
            $arBasename = array_slice($arBasename, 0, -1);
        }
        return implode('', $arBasename);
    }

    private function getFilePath($sFilename)
    {
        if (file_exists($sFilename)) {
            return $sFilename;
        }

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $sFilename)) {
            return $_SERVER['DOCUMENT_ROOT'] . $sFilename;
        }

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $sFilename)) {
            return $_SERVER['DOCUMENT_ROOT'] . '/' . $sFilename;
        }

        return false;
    }

    private static function responseError($sText = 'file not found')
    {
        return $sText;
    }
}