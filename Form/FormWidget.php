<?php

namespace ArsThanea\PageMediaSetBundle\Form;

use ArsThanea\PageMediaSetBundle\Entity\PageMedia;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;

class FormWidget extends \Kunstmaan\AdminBundle\Helper\FormWidgets\FormWidget
{
    /**
     * @var PageMediaCollectionAdminType
     */
    private $type;
    /**
     * @var Collection
     */
    private $mediaSet;
    /**
     * @var PageMedia[]
     */
    private $toDelete;

    public function __construct(PageMediaCollectionAdminType $type, Collection $mediaSet)
    {
        parent::__construct(['media_set' => $type]);
        $this->type = $type;
        $this->mediaSet = $mediaSet;
        $this->toDelete = $mediaSet->toArray();
    }


    /**
     * @param FormBuilderInterface $builder The form builder
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder->add('media_set', $this->type, [
            'data' => $this->mediaSet,
        ]);
    }


    /**
     * @param EntityManager $em The entity manager
     */
    public function persist(EntityManager $em)
    {
        foreach ($this->mediaSet->filter(function (PageMedia $item) {
            return (bool) $item->getMedia();
        }) as $item) {
            $em->persist($item);
        }

        foreach ($this->toDelete as $item) {

            $callback = function ($key, PageMedia $element) use ($item) {
                /** @noinspection PhpExpressionResultUnusedInspection */ $key;
                return $element->getType() === $item->getType() && $element->getMedia();
            };

            if (false === $this->mediaSet->exists($callback)) {
                $em->remove($item);
            }
        }
    }

}