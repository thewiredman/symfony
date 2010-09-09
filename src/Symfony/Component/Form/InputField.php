<?php

namespace Symfony\Component\Form;

/**
 * Base class for all low-level fields represented by input tags
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
abstract class InputField extends Field
{
    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return array_merge(parent::getAttributes(), array(
            'id'       => $this->getId(),
            'name'     => $this->getName(),
            'value'    => $this->getDisplayedData(),
            'disabled' => $this->isDisabled(),
        ));
    }
}
