<?php

namespace ArsThanea\PageMediaSetBundle\Service;

class MediaFormat
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $maxWidth;
    /**
     * @var int
     */
    private $minHeight;
    /**
     * @var int
     */
    private $maxHeight;
    /**
     * @var int
     */
    private $minWidth;

    public function __construct($name, $minWidth, $minHeight, $maxWidth = null, $maxHeight = null)
    {
        $this->name = $name;
        $this->maxWidth = $maxWidth;
        $this->minHeight = $minHeight;
        $this->maxHeight = $maxHeight;
        $this->minWidth = $minWidth;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * @return int
     */
    public function getMinHeight()
    {
        return $this->minHeight;
    }

    /**
     * @return int
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * @return int
     */
    public function getMinWidth()
    {
        return $this->minWidth;
    }


}