<?php

namespace ArsThanea\PageMediaSetBundle\Twig;

use ArsThanea\PageMediaSetBundle\Service\HasMediaSetInterface;
use ArsThanea\PageMediaSetBundle\Service\PageMediaSetService;

class PageMediaSetTwigExtension extends \Twig_Extension
{
    /**
     * @var PageMediaSetService
     */
    private $mediaSetService;

    public function __construct(PageMediaSetService $mediaSetService)
    {
        $this->mediaSetService = $mediaSetService;
    }

    public function getFunctions()
    {
        return [
            'page_media' => new \Twig_SimpleFunction('page_media', [$this, 'getPageMedia']),
        ];
    }

    public function getPageMedia($page, $type = null)
    {
        if ($page instanceof HasMediaSetInterface) {
            return $this->mediaSetService->getPageMedia($page, $type) ? : "";
        }

        return "";
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'page_media_set';
    }
}
