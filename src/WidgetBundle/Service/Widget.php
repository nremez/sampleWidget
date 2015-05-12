<?php

namespace WidgetBundle\Service;


class Widget {
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $backgroundColor;

    /**
     * @var int
     */
    private $textColor;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */

    private $filename = 'widget.jpg';
    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }


    // Rendering constants
    const STRING_Y = 22;

    const FONT = 5;


    public function __construct($width, $height, $backgroundColor, $textColor, $text)
    {
        $this->width = $width;
        $this->height = $height;
        $this->backgroundColor = $backgroundColor;
        $this->textColor = $textColor;
        $this->text = $text;

    }

    /**
     * @return int
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param int $backgroundColor
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * @param int $textColor
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }



    public function render()
    {

        $image = @imagecreate($this->width, $this->height)
            or die("Cannot Initialize new GD image stream");

        $background_color = $this->hextToRGBColorAllocate($image, $this->backgroundColor);
        $text_color = $this->hextToRGBColorAllocate($image, $this->textColor);

        // center-align label on x-acis
        $stringX = (imagesx($image) - 7.5 * strlen($this->text)) / 2;

        @imagestring($image, self::FONT, $stringX, self::STRING_Y, $this->text, $text_color);
        @imagejpeg($image);

        return $image;
    }


    private function hextToRGBColorAllocate($image, $hex) {
        $hex = ltrim($hex,'#');
        $a = hexdec(substr($hex, 0, 2));
        $b = hexdec(substr($hex, 2, 2));
        $c = hexdec(substr($hex, 4, 2));
        return imagecolorallocate($image, $a, $b, $c);
    }

}