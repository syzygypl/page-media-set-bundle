<?php

namespace ArsThanea\PageMediaSetBundle\Form;

use ArsThanea\PageMediaSetBundle\Entity\PageMedia;
use ArsThanea\PageMediaSetBundle\Service\HasMediaSetInterface;
use ArsThanea\PageMediaSetBundle\Service\MediaSetDefinitionInterface;
use ArsThanea\PageMediaSetBundle\Service\HasRichMediaInterface;
use ArsThanea\PageMediaSetBundle\Service\MediaSetDefinitionService;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageMediaCollectionAdminType extends AbstractType
{
    /**
     * @var MediaSetDefinitionInterface
     */
    private $mediaSetDefinition;

    public function __construct(MediaSetDefinitionInterface $mediaSetDefinition = null)
    {
        $this->mediaSetDefinition = $mediaSetDefinition ?: new MediaSetDefinitionService([]); // TODO
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var HasMediaSetInterface */
        $page = $options['page'];


        $builder->add('page_media_set', CollectionType::class, [
            'label' => false,
            'allow_delete' => true,
            'allow_add' => false,
            'entry_type' => PageMediaAdminType::class,
            'entry_options' => [
                'media_types' => $this->mediaSetDefinition->getMediaSetDefinition($page),
                'images_only' => false === $page instanceof HasRichMediaInterface
            ],
            'attr' => [
                'nested_form' => true,
            ],
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($page) {
            /** @var Collection $data */
            $data = $event->getData();

            $mediaSet = $this->mediaSetDefinition->getMediaSetDefinition($page);

            $types = array_map(function ($type) use ($data, $page) {
                return $data->filter(function (PageMedia $element) use ($type, $page) {
                    return $type === $element->getType();
                })->first() ? : (new PageMedia)->setType($type)->setPageId($page->getId())->setPageType($page->getType());
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('page');
        $resolver->setAllowedTypes('page', [HasMediaSetInterface::class]);
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
