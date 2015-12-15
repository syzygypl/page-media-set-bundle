<?php

namespace ArsThanea\PageMediaSetBundle\Service;

use ArsThanea\PageMediaSetBundle\Entity\PageMedia;
use ArsThanea\PageMediaSetBundle\Entity\PageMediaRepository;
use Doctrine\ORM\Query\Expr\Join;
use Kunstmaan\MediaBundle\Entity\Media;

class PageMediaSetService
{

    /**
     * @var array
     */
    private $mediaSets = [];

    private $preloadedMediaSets = false;

    /**
     * @var PageMediaRepository
     */
    private $repository;

    public function __construct(PageMediaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPageMedia(HasMediaSetInterface $page, $name = null)
    {
        if (null === $name) {
            list ($name) = $page->getMediaSetDefinition();
        }

        $mediaSet = $this->getPageMediaSet($page);

        if (false === isset($mediaSet[$name])) {
            return null;
        }

        return $mediaSet[$name];
    }

    /**
     * @param HasMediaSetInterface $page
     *
     * @return PageMedia[]
     */
    private function getPageMediaSet(HasMediaSetInterface $page)
    {
        if (false === $this->preloadedMediaSets) {
            $data = $this->repository
                ->createQueryBuilder('pm')
                ->join(Media::class, 'media', Join::WITH, 'pm.media = media.id')
                ->select(['pm.pageType', 'pm.pageId', 'pm.type', 'media.url'])
                ->getQuery()
                ->getResult();

            $this->preloadedMediaSets = true;

            foreach ($data as $media) {
                $this->mediaSets[$media['pageType']][$media['pageId']][$media['type']] = $media['url'];
            }
        }

        if (false === isset($this->mediaSets[$page->getType()][$page->getId()])) {
            return [];
        }

        return $this->mediaSets[$page->getType()][$page->getId()];
    }

}