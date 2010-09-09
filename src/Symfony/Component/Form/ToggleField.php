<?php

namespace Symfony\Component\Form;

use Symfony\Component\Form\ValueTransformer\BooleanToStringTransformer;

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * An input field for selecting boolean values.
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
abstract class ToggleField extends InputField
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addOption('value');
        $this->addOption('label');

        $this->setValueTransformer(new BooleanToStringTransformer());
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return array_merge(parent::getAttributes(), array(
            'value'   => $this->getOption('value'),
            'checked' => (string) $this->getDisplayedData() !== '' && $this->getDisplayedData() !== 0,
        ));
    }
}
