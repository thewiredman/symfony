<?php

namespace Symfony\Component\Form;

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A radio field for selecting boolean values.
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
class RadioField extends ToggleField
{
    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return array_merge(parent::getAttributes(), array(
            'type' => 'radio',
            'name' => $this->getParent() ? $this->getParent()->getName() : $this->getName(),
        ));
    }
}
