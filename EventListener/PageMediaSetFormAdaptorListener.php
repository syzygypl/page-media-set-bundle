<?php

namespace ArsThanea\PageMediaSetBundle\EventListener;

use ArsThanea\PageMediaSetBundle\Entity\PageMediaRepository;
use ArsThanea\PageMediaSetBundle\Form\FormWidget;
use ArsThanea\PageMediaSetBundle\Service\HasMediaSetInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Kunstmaan\AdminBundle\Helper\FormWidgets\Tabs\Tab;
use Kunstmaan\NodeBundle\Event\AdaptFormEvent;

class PageMediaSetFormAdaptorListener
{

    /**
     * @var PageMediaRepository
     */
    private $repository;

    public function __construct(PageMediaRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AdaptFormEvent $event
     */
    public function adaptForm(AdaptFormEvent $event)
    {
        /** @var HasMediaSetInterface $page */
        $page = $event->getPage();
        if (false === $page instanceof HasMediaSetInterface) {
            return;
        }

        $mediaSet = $this->repository->getPageMediaSet($page);

        $mediaWidget = new FormWidget($page, new ArrayCollection($mediaSet));

        $event->getTabPane()->addTab(new Tab('Media Set', $mediaWidget));
    }

}
