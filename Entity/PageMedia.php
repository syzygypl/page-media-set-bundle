<?php

namespace ArsThanea\PageMediaSetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kunstmaan\MediaBundle\Entity\Media;

/**
 * PageMedia
 *
 * @ORM\Table(name="page_media", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="page_type", columns={"page_type", "page_id", "type"})
 * })
 * @ORM\Entity(repositoryClass="ArsThanea\PageMediaSetBundle\Entity\PageMediaRepository")
 */
class PageMedia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="page_id", type="integer", nullable=false)
     */
    private $pageId;

    /**
     * @var string
     *
     * @ORM\Column(name="page_type", type="string", nullable=false)
     */
    private $pageType;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Kunstmaan\MediaBundle\Entity\Media", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $media;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set media
     *
     * @param Media $media
     *
     * @return PageMedia
     */
    public function setMedia(Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return PageMedia
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * @param int $pageId
     *
     * @return $this
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPageType()
    {
        return $this->pageType;
    }

    /**
     * @param string $pageType
     *
     * @return $this
     */
    public function setPageType($pageType)
    {
        $this->pageType = $pageType;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->id;
    }

}

