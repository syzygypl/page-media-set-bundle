<?php

namespace ArsThanea\PageMediaSetBundle\Service;

interface MediaSetDefinitionInterface
{
    /**
     * @param HasMediaSetInterface $object
     * @return array
     */
    public function getMediaSetDefinition(HasMediaSetInterface $object);

    /**
     * @param string $type
     * @return array
     */
    public function getDefaultMediaSetDefinition($type);
}
