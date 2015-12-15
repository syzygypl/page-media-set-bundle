<?php

namespace ArsThanea\PageMediaSetBundle\Service;

interface HasMediaSetInterface 
{
    /**
     * Return list of media format this entity supports.
     *
     * For instance: ['banner', 'teaser']
     *
     * @return array
     */
    public function getMediaSetDefinition();

    /**
     * @return int
     */
    public function getId();

    /**
     * Page type. It should be distinct among all pages. You may just return `self::class`
     *
     * @return string
     */
    public function getType();
}
