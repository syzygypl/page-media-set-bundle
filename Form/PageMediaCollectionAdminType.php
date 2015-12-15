<?php

namespace ArsThanea\PageMediaSetBundle\Form;

use ArsThanea\PageMediaSetBundle\Entity\PageMedia;
use ArsThanea\PageMediaSetBundle\Service\HasMediaSetInterface;
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

    public function __construct(HasMediaSetInterface $page)
    {
        $this->page = $page;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('page_media_set', 'collection', [
            'label'        => false,
            'allow_delete' => true,
            'allow_add'    => false,
            'type'         => 'page_media',
            'options'      => [
                'media_types' => $this->page->getMediaSetDefinition(),
            ],
            'attr'         => [
                'nested_form' => true,
            ],
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Collection $data */
            $data = $event->getData();

            $types = array_map(function ($type) use ($data) {
                return $data->filter(function (PageMedia $element) use ($type) {
                    return $type === $element->getType();
                })->first() ? : (new PageMedia)->setType($type)->setPageId($this->page->getId())->setPageType($this->page->getType());
            }, array_combine($this->page->getMediaSetDefinition(), $this->page->getMediaSetDefinition()));

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
    public function getName()
    {
        return 'page_media_collection';
    }
}