<?php

namespace ArsThanea\PageMediaSetBundle\EventListener;

use ArsThanea\PageMediaSetBundle\Service\HasMediaSetInterface;
use ArsThanea\PageMediaSetBundle\Service\PageMediaSetService;
use Kunstmaan\NodeSearchBundle\Event\IndexNodeEvent;

class ThumbnailIndexerListener
{
    /**
     * @var PageMediaSetService
     */
    private $mediaSetService;

    public function __construct(PageMediaSetService $mediaSetService)
    {
        $this->mediaSetService = $mediaSetService;
    }

    public function onIndexNode(IndexNodeEvent $event)
    {
        $page = $event->getPage();

        if ($page instanceof HasMediaSetInterface) {
            $event->doc['photo'] = parse_url($this->mediaSetService->getPageMedia($page), PHP_URL_PATH);
        }
    }

}
