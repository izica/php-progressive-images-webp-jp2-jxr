## Desription
Library converts your images to progressive images formats

* Webp
* Jpeg 2000
* Jpeg XR

## Warning
Currently works only on Unix systems, you can help me achieve cross-platform waiting for your pull requests.

## Requirements
```shell script
imagemagick
webp
libjxr-tools
```

## Installation
```
sudo apt-get install imagemagick webp libjxr-tools
composer require izica/php-progressive-images
```

## Usage(Examples)
```php
<?php
$obData = \Izica\ProgressiveImages::fromFileSource('/upload/iblock/fe7/fe7728c5f2c6763693eb1d9ef105c46c.png')
    ->setFileName('custom-file-name')
    ->setDestinationFolder($_SERVER['DOCUMENT_ROOT'] . '/test/cache/')
    ->convert();
/*

Izica\ProgressiveImagesResponse Object
(
    [source] => /upload/iblock/fe7/fe7728c5f2c6763693eb1d9ef105c46c.png
    [webp] => /test/cache/custom-file-name.webp
    [jpeg2000] => /test/cache/custom-file-name.jp2
    [jpegxr] => /test/cache/custom-file-name.jxr
)
 */
?>

<picture>
    <source srcset="<?=$obData->jpegxr;?>" type='image/vnd.ms-photo'>
    <source srcset="<?=$obData->jpeg2000;?>" type='image/jp2'>
    <source srcset="<?=$obData->webp;?>" type="image/webp">
    <img src="<?=$obData->source;?>" alt="">
</picture>

```

```php
$arData = \Izica\ProgressiveImages::fromFileSource('/upload/iblock/fe7/fe7728c5f2c6763693eb1d9ef105c46c.png')
    ->setFileName('custom-file-name')
    ->setDestinationFolder($_SERVER['DOCUMENT_ROOT'] . '/test/cache/')
    ->convert()
    ->toArray();
/*
    Array
    (
        [source] => /upload/iblock/fe7/fe7728c5f2c6763693eb1d9ef105c46c.png
        [webp] => /test/cache/custom-file-name.webp
        [jpeg2000] => /test/cache/custom-file-name.jp2
        [jpegxr] => /test/cache/custom-file-name.jxr
    )
 */
```


```php
$arData = \Izica\ProgressiveImages::fromFileSource('/upload/iblock/fe7/fe7728c5f2c6763693eb1d9ef105c46c.png')
    ->setFileName('custom-file-name')
    ->setDestinationFolder($_SERVER['DOCUMENT_ROOT'] . '/test/cache/')
    ->withoutJpegXr()
    ->withoutJpeg2000()
    ->convert();
/*
 Izica\ProgressiveImagesResponse Object
   (
       [source] => /upload/iblock/fe7/fe7728c5f2c6763693eb1d9ef105c46c.png
       [webp] => /test/cache/custom-file-name.webp
       [jpeg2000] => 
       [jpegxr] => 
   )
 */
```