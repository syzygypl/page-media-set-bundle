<?php

namespace ArsThanea\PageMediaSetBundle\Service;

class MediaSetDefinitionService implements MediaSetDefinitionInterface
{
    /**
     * @var array
     */
    private $types;

    /**
     * @param array $types
     */
    public function __construct(array $types)
    {
        $this->types = array_map(function ($v) { return (array)$v; }, $types);
    }


    /**
     * @param HasMediaSetInterface $object
     * @return array
     */
    public function getMediaSetDefinition(HasMediaSetInterface $object)
    {
        if (isset($this->types[$object->getType()])) {
            return (array)$this->types[$object->getType()];
        }

        return $object->getMediaSetDefinition();
    }

    /**
     * @param string $type
     * @return array
     */
    public function getDefaultMediaSetDefinition($type)
    {
        if (isset($this->types[$type])) {
            return $this->types[$type];
        }

        return [];
    }
}
