<?php

namespace ArsThanea\PageMediaSetBundle\Form;

use ArsThanea\PageMediaSetBundle\Entity\PageMedia;
use ArsThanea\PageMediaSetBundle\Service\HasMediaSetInterface;
use ArsThanea\PageMediaSetBundle\Service\MediaSetDefinitionInterface;
use ArsThanea\PageMediaSetBundle\Service\HasRichMediaInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageMediaCollectionAdminType extends AbstractType
{
    /**
     * @var HasMediaSetInterface
     */
    private $page;

    /**
     * @var MediaSetDefinitionInterface
     */
    private $mediaSetDefinition;

    public function __construct(HasMediaSetInterface $page, MediaSetDefinitionInterface $mediaSetDefinition)
    {
        $this->page = $page;
        $this->mediaSetDefinition = $mediaSetDefinition;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('page_media_set', 'collection', [
            'label'        => false,
            'allow_delete' => true,
            'allow_add'    => false,
            'type'         => PageMediaAdminType::class,
            'options'      => [
                'media_types' => $this->mediaSetDefinition->getMediaSetDefinition($this->page),
                'images_only' => false === $this->page instanceof HasRichMediaInterface
            ],
            'attr'         => [
                'nested_form' => true,
            ],
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Collection $data */
            $data = $event->getData();

            $mediaSet = $this->mediaSetDefinition->getMediaSetDefinition($this->page);

            $types = array_map(function ($type) use ($data) {
                return $data->filter(function (PageMedia $element) use ($type) {
                    return $type === $element->getType();
                })->first() ? : (new PageMedia)->setType($type)->setPageId($this->page->getId())->setPageType($this->page->getType());
            }, array_combine($mediaSet, $mediaSet));

            $data->clear();
            foreach ($types as $key => $item) {
                $data->set($key, $item);
            }

            $event->setData([
                'page_media_set' => $data,
            ]);

        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'page_media_collection';
    }
}
