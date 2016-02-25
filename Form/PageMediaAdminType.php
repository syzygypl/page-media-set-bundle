<?php

namespace ArsThanea\PageMediaSetBundle\Form;

use ArsThanea\PageMediaSetBundle\Entity\PageMedia;
use ArsThanea\PageMediaSetBundle\Service\FormatRepository;
use Kunstmaan\MediaBundle\Form\Type\MediaType;
use Kunstmaan\MediaBundle\Validator\Constraints\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageMediaAdminType extends AbstractType
{

    /**
     * @var FormatRepository
     */
    private $repository;

    public function __construct(FormatRepository $repository)
    {
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('media', MediaType::class, [
            'pattern'   => 'KunstmaanMediaBundle_chooser',
            'mediatype' => 'image',
            'required'  => true,
        ]);

        $mediaTypes = array_map(function ($type) {
            return 'page_media_set.format.' . $type;
        }, array_combine($options['media_types'], $options['media_types']));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder, $mediaTypes) {
            /** @var PageMedia $data */
            $data = $event->getData();
            $form = $event->getForm();

            if (null === $data) {
                $form->add('type', ChoiceType::class, [
                    'choices' => $mediaTypes,
                ]);

                return;
            }

            $label = $mediaTypes[$data->getType()];
            $format = $this->repository->get($data->getType());
            $options = [
                'label' => $label,
                'attr' => [
                    'info_text' => ($format ? sprintf('%d × %d', $format->getMaxWidth(), $format->getMinHeight()) : "")
                ],
                'constraints' => $format ? [new Media([
                    'minWidth' => $format->getMinWidth(),
                    'minHeight' => $format->getMinHeight(),
                    'maxWidth' => $format->getMaxWidth(),
                    'maxHeight' => $format->getMaxHeight(),
                ])] : [],
            ];
            $form->add('media', $builder->get('media')->getType()->getName(), $options + $builder->get('media')->getOptions());

        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PageMedia::class
        ]);
        $resolver->setRequired(['media_types']);
        $resolver->setAllowedTypes([
            'media_types' => ['array']
        ]);
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'page_media';
    }
}
