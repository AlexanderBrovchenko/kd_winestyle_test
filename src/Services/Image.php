<?php

namespace App\Services;

class Image
{
    public $supported_img_types;
    public $type;
    public $width;
    public $height;
    public $image;
    public function __construct($file = null)
    {
        if (!$file) {
            trigger_error("Parameter file undefined.", E_USER_ERROR);
        }
        if (!file_exists($file)) {
            trigger_error("File '{$file}' not found.", E_USER_ERROR);
        }
        $this->check_php_gd();
        $this->supported_img_types = $this->supported_image_types();
        $this->open_image($file);
    }
    public function open_image($file = null)
    {
        $image_type = $this->image_type($file);
        if (!$image_type) {
            trigger_error("Invalid image file: {$file}.", E_USER_ERROR);
        }
        $this->type = $image_type[0];
        $this->width = $image_type[1];
        $this->height = $image_type[2];
        if (!in_array($this->type, $this->supported_img_types)) {
            trigger_error("File type '{$this->type}' not supported.", E_USER_ERROR);
        }
        // Destroy if already opened
        if ($this->image) {
            ImageDestroy($this->image);
        }
        switch ($this->type) {
            case 'JPG':
                $this->image = ImageCreateFromJPEG($file);
                break;
            case 'GIF':
                $this->image = ImageCreateFromGIF($file);
                break;
            case 'PNG':
                $this->image = ImageCreateFromPNG($file);
                break;
            case 'WEBP':
                $this->image = ImageCreateFromWebp($file);
                break;
            case 'WBMP':
                $this->image = ImageCreateFromWebp($file);
                break;
            case 'XBM':
                $this->image = ImageCreateFromWebp($file);
                break;
        }
    }
    public function output()
    {
        header("Content-type:image/jpeg");
        imagejpeg($this->image);
    }

    public function resize($width = null, $height = null)
    {
        if (!is_numeric($width) || $width < 1) {
            trigger_error("Invalid width: {$width}.", E_USER_ERROR);
        }
        if (!is_numeric($height) || $height < 1) {
            trigger_error("Invalid height: {$height}.", E_USER_ERROR);
        }
        $temp = $this->image;
        $this->image = ImageCreateTrueColor($width, $height);
        ImageCopyResampled($this->image, $temp, 0, 0, 0, 0, $width + 1, $height + 1, $this->width, $this->height);
        ImageDestroy($temp);
        $this->width = $width;
        $this->height = $height;
        return array($width, $height);
    }
    public function resize_w($width = null)
    {
        if (!is_numeric($width) || $width < 1) {
            trigger_error("Invalid width: {$width}.", E_USER_ERROR);
        }
        $height = $this->height * $width / $this->width;
        return $this->resize($width, $height);
    }
    public function resize_h($height = null)
    {
        if (!is_numeric($height) || $height < 1) {
            trigger_error("Invalid height: {$height}.", E_USER_ERROR);
        }
        $width = $this->width * $height / $this->height;
        return $this->resize($width, $height);
    }
    public function scale_to_fit($width = null, $height = null)
    {
        if (!is_numeric($width) || $width < 1) {
            trigger_error("Invalid width: {$width}.", E_USER_ERROR);
        }
        if (!is_numeric($height) || $height < 1) {
            trigger_error("Invalid height: {$height}.", E_USER_ERROR);
        }
        $img_ratio = $this->width / $this->height;
        $ratio = $width / $height;
        if ($img_ratio <= $ratio) {
            return $this->resize_h($height);
        } else {
            return $this->resize_w($width);
        }
    }
    public function set_transparent_color($r, $g, $b)
    {
        $r = $this->color($r);
        $g = $this->color($g);
        $b = $this->color($b);
        $color_index = ImageColorExact($this->image, $r, $g, $b);
        ImageColorTransparent($this->image, $color_index);
    }
    public function save($directory = "", $name = "", $type = "", $overwrite = true, $quality = 100)
    {
        if ($directory) {
            if (!file_exists($directory)) {
                if (!@mkdir($directory, 0777)) {
                    trigger_error("Error creating directory '{$directory}'.", E_USER_ERROR);
                }
            }
        }
        if (!$name) {
            trigger_error("Enter a valid file name.", E_USER_ERROR);
        }
        if (!$type) {
            $type = $this->type;
        }
        if (!in_array($type, $this->supported_img_types)) {
            trigger_error("File type '{$type}' not supported. Use Only:(JPG, GIF, PNG).", E_USER_ERROR);
        }
        $filename = $directory . $name . "." . strtolower($type);
        if (file_exists($filename) && !$overwrite) {
            trigger_error("Cannot overwrite.", E_USER_ERROR);
        }
        switch ($type) {
            case 'JPG':
                ImageJPEG($this->image, $filename, $quality);
                break;
            case 'GIF':
                ImageGIF($this->image, $filename, $quality);
                break;
            case 'PNG':
                ImagePNG($this->image, $filename, $quality);
                break;
        }
        return $filename;
    }

    public function check_php_gd()
    {
        if (!extension_loaded('gd')) {
            trigger_error("GD Library Not Found.", E_USER_ERROR);
        }
    }
    public function supported_image_types()
    {
        $supported = array();
        $possibles = array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 15 => 'WBMP', 16 => 'XBM', 18 => 'WEBP');
        foreach ($possibles as $i_type => $s_type) {
            if (imagetypes() & $i_type) {
                $supported[] = $s_type;
            }
        }
        return $supported;
    }
    public function image_type($file)
    {
        if (!$file || !@GetImageSize($file)) {
            return null;
        }
        $image_size = GetImageSize($file);
        $image_type = $image_size[2];
        $possibles = array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF', 5 => 'PSD', 6 => 'BMP', 7 => 'TIFF(intel byte order)', 8 => 'TIFF(motorola byte order)', 9 => 'JPC', 10 => 'JP2', 11 => 'JPX', 12 => 'JB2', 13 => 'SWC', 14 => 'IFF', 15 => 'WBMP', 16 => 'XBM', 18 => 'WEBP');
        foreach ($possibles as $key => $type) {
            if ($image_type == $key) {
                return array($type, $image_size[0], $image_size[1]);
            }
        }
        return null;
    }
    public function image_position($width, $height, $width2, $height2, $pos = 'CENTER')
    {
        switch ($pos) {
            case 'CENTER':
                $x = ($width - $width2) / 2;
                $y = ($height - $height2) / 2;
                break;
            case 'CENTER_RIGHT':
                $x = $width - $width2;
                $y = ($height - $height2) / 2;
                break;
            case 'CENTER_LEFT':
                $x = 0;
                $y = ($height - $height2) / 2;
                break;
            case 'TOP':
                $x = ($width - $width2) / 2;
                $y = 0;
                break;
            case 'TOP_RIGHT':
                $x = $width - $width2;
                $y = 0;
                break;
            case 'TOP_LEFT':
                $x = 0;
                $y = 0;
                break;
            case 'DOWN':
                $x = ($width - $width2) / 2;
                $y = $height - $height2;
                break;
            case 'DOWN_RIGHT':
                $x = $width - $width2;
                $y = $height - $height2;
                break;
            case 'DOWN_LEFT':
                $x = 0;
                $y = $height - $height2;
                break;
            default:
                $x = ($width - $width2) / 2;
                $y = ($height - $height2) / 2;
        }
        return array($x, $y);
    }
    public function color($color)
    {
        if ($color < 0) {
            $color = 0;
        } else {
            if ($color > 255) {
                $color = 255;
            }
        }
        return $color;
    }
}