<?php

namespace ArsThanea\PageMediaSetBundle\EventListener;

use ArsThanea\PageMediaSetBundle\Entity\PageMediaRepository;
use ArsThanea\PageMediaSetBundle\Form\FormWidget;
use ArsThanea\PageMediaSetBundle\Form\PageMediaCollectionAdminType;
use ArsThanea\PageMediaSetBundle\Service\HasMediaSetInterface;
use ArsThanea\PageMediaSetBundle\Service\MediaSetDefinitionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Kunstmaan\AdminBundle\Helper\FormWidgets\Tabs\Tab;
use Kunstmaan\NodeBundle\Event\AdaptFormEvent;

class PageMediaSetFormAdaptorListener
{

    /**
     * @var PageMediaRepository
     */
    private $repository;

    /**
     * @var MediaSetDefinitionInterface
     */
    private $mediaSetDefinition;

    public function __construct(PageMediaRepository $repository, MediaSetDefinitionInterface $mediaSetDefinition)
    {
        $this->repository = $repository;
        $this->mediaSetDefinition = $mediaSetDefinition;
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

        $type = new PageMediaCollectionAdminType($page, $this->mediaSetDefinition);
        $mediaWidget = new FormWidget($type, new ArrayCollection($mediaSet));

        $event->getTabPane()->addTab(new Tab('Media Set', $mediaWidget));
    }

}
