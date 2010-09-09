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
 * A file field to upload files.
 */
class FileField extends InputField
{
    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return array_merge(parent::getAttributes(), array(
            'type' => 'file',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function isMultipart()
    {
        return true;
    }
}
