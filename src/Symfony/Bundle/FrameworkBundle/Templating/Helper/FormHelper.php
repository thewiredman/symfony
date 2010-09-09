<?php

namespace Symfony\Bundle\FrameworkBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Templating\Engine;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FieldInterface;
use Symfony\Component\Form\HybridField;
use Symfony\Component\Form\FieldGroupInterface;
use Symfony\Bundle\FrameworkBundle\Templating\HtmlGeneratorInterface;

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * FormHelper.
 *
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class FormHelper extends Helper implements \IteratorAggregate
{
    public $generator;

    protected $engine;
    protected $group;
    protected $cache;
    protected $theme;

    public function __construct(Engine $engine, HtmlGeneratorInterface $generator)
    {
        $this->engine = $engine;
        $this->generator = $generator;
        $this->cache = array();
    }

    public function create(FieldGroupInterface $group, $theme = 'table', $doctype = 'xhtml')
    {
        $helper = clone $this;
        $helper->setFieldGroup($group);
        $helper->setTheme($theme);

        return $helper;
    }

    /**
     * Renders the form tag.
     *
     * This method only renders the opening form tag.
     * You need to close it after the form rendering.
     *
     * This method takes into account the multipart widgets.
     *
     * @param  string $url         The URL for the action
     * @param  array  $attributes  An array of HTML attributes
     *
     * @return string An HTML representation of the opening form tag
     */
    public function action($url, array $attributes = array())
    {
/*
        return sprintf('<form%s>', $this->generator->attributes(array_merge(array(
            'action' => $url,
            'method' => isset($attributes['method']) ? strtolower($attributes['method']) : 'post',
            'enctype' => $this->isMultipart() ? 'multipart/form-data' : null,
        ), $attributes)));
*/
    }

    public function group($template = null)
    {
        if (null === $this->group) {
            throw new \LogicException('You must set the form you want to operate on first.');
        }

        if (null === $template) {
            $template = sprintf('FrameworkBundle:Form:group/%s/FieldGroup', $this->theme);
        }

        return $this->engine->render($template, array('form' => $this, 'group' => $this->group));
    }

    public function hidden($template = null)
    {
        if (null === $this->group) {
            throw new \LogicException('You must set the form you want to operate on first.');
        }

        if (null === $template) {
            $template = 'FrameworkBundle:Form:hidden';
        }

        return $this->engine->render($template, array('form' => $this, 'group' => $this->group));
    }

    public function errors($template = null)
    {
        if (null === $this->group) {
            throw new \LogicException('You must set the form you want to operate on first.');
        }

        if (null === $template) {
            $template = 'FrameworkBundle:Form:errors';
        }

        return $this->engine->render($template, array('form' => $this, 'group' => $this->group, 'field' => $this->group));
    }

    public function row($field, $template = null)
    {
        if (null === $this->group) {
            throw new \LogicException('You must set the form you want to operate on first.');
        }

        if (is_string($field)) {
            $field = $this->group[$field];
        }

        if (null === $template) {
            $template = sprintf('FrameworkBundle:Form:group/%s/row', $this->theme);
        }

        return $this->engine->render($template, array('form' => $this, 'group' => $this->group, 'field' => $field));
    }

    public function field($field, array $attributes = array(), $template = null)
    {
        if (null === $this->group) {
            throw new \LogicException('You must set the form you want to operate on first.');
        }

        if (is_string($field)) {
            $field = $this->group[$field];
        }

        if ($field instanceof FieldGroupInterface && !$field instanceof HybridField) {
            return $this->create($field)->group($template);
        }

        if (null === $template) {
            $template = $this->getFieldTemplate($field, $template);
        }

        return $this->engine->render($template, array(
            'form'       => $this,
            'group'      => $this->group,
            'field'      => $field,
            'attributes' => array_merge($field->getAttributes(), $attributes),
        ));
    }

    public function label($field, $template = null)
    {
        if (null === $this->group) {
            throw new \LogicException('You must set the form you want to operate on first.');
        }

        if (is_string($field)) {
            $field = $this->group[$field];
        }

        if (null === $template) {
            $template = 'FrameworkBundle:Form:label';
        }

        return $this->engine->render($template, array('form' => $this, 'group' => $this->group, 'field' => $field));
    }

    public function error($field, $template = null)
    {
        if (null === $this->group) {
            throw new \LogicException('You must set the form you want to operate on first.');
        }

        if (is_string($field)) {
            $field = $this->group[$field];
        }

        if (null === $template) {
            $template = 'FrameworkBundle:Form:errors';
        }

        return $this->engine->render($template, array('form' => $this, 'group' => $this->group, 'field' => $field));
    }

    public function humanize($text)
    {
        return ucfirst(strtolower(str_replace('_', ' ', $text)));
    }

    public function getIterator()
    {
        return $this->group->getIterator();
    }

    protected function getFieldTemplate(FieldInterface $field, $template = null)
    {
        $class = get_class($field);

        if (isset($this->cache[$class])) {
            return $this->cache[$class];
        }

        if (null === $template) {
            // find a template for the given class or one of its parents
            do {
                $parts = explode('\\', $class);
                $c = array_pop($parts);
                if ($this->engine->exists('FrameworkBundle:Form:field/'.$c)) {
                    $template = 'FrameworkBundle:Form:field/'.$c;

                    break;
                }
            } while (false !== $class = get_parent_class($class));
        }

        return $template;
    }

    // do not user directly
    public function setFieldGroup(FieldGroupInterface $group)
    {
        $this->group = $group;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return '__form';
    }
}
