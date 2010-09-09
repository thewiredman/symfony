<?php

namespace Symfony\Component\Form;

use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Lets the user select between different choices
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
class ChoiceField extends HybridField
{
    /**
     * Stores the preferred choices with the choices as keys
     * @var array
     */
    protected $preferredChoices = array();

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addRequiredOption('choices');
        $this->addOption('preferred_choices', array());
        $this->addOption('separator', '----------');
        $this->addOption('multiple', false);
        $this->addOption('expanded', false);
        $this->addOption('empty_value', '');

        if (!is_array($this->getOption('choices'))) {
            throw new UnexpectedTypeException('The choices option must be an array');
        }

        if (!is_array($this->getOption('preferred_choices'))) {
            throw new UnexpectedTypeException('The preferred_choices option must be an array');
        }

        if (count($this->getOption('preferred_choices')) > 0) {
            $this->preferredChoices = array_flip($this->getOption('preferred_choices'));
        }

        if ($this->getOption('expanded')) {
            $this->setFieldMode(self::GROUP);

            $choices = $this->getOption('choices');

            foreach ($this->preferredChoices as $choice => $_) {
                $this->add($this->newChoiceField($choice, $choices[$choice]));
            }

            foreach ($choices as $choice => $value) {
                if (!isset($this->preferredChoices[$choice])) {
                    $this->add($this->newChoiceField($choice, $value));
                }
            }
        } else {
            $this->setFieldMode(self::FIELD);
        }
    }

    public function getPreferredChoices()
    {
        return $this->preferredChoices;
    }

    /**
     * Returns a new field of type radio button or checkbox.
     *
     * @param string $key      The key for the option
     * @param string $label    The label for the option
     */
    protected function newChoiceField($choice, $label)
    {
        if ($this->getOption('multiple')) {
            return new CheckboxField($choice, array(
                'value' => $choice,
                'label' => $label,
            ));
        } else {
            return new RadioField($choice, array(
                'value' => $choice,
                'label' => $label,
            ));
        }
    }

    /**
     * {@inheritDoc}
     *
     * Takes care of converting the input from a single radio button
     * to an array.
     */
    public function bind($value)
    {
        if (!$this->getOption('multiple') && $this->getOption('expanded')) {
            $value = $value === null ? array() : array($value => true);
        }

        parent::bind($value);
    }

    /**
     * Transforms a single choice or an array of choices to a format appropriate
     * for the nested checkboxes/radio buttons.
     *
     * The result is an array with the options as keys and true/false as values,
     * depending on whether a given option is selected. If this field is rendered
     * as select tag, the value is not modified.
     *
     * @param  mixed $value  An array if "multiple" is set to true, a scalar
     *                       value otherwise.
     * @return mixed         An array if "expanded" or "multiple" is set to true,
     *                       a scalar value otherwise.
     */
    protected function transform($value)
    {
        if ($this->getOption('expanded')) {
            $choices = $this->getOption('choices');

            foreach ($choices as $choice => $_) {
                $choices[$choice] = $this->getOption('multiple')
                    ? in_array($choice, (array)$value, true)
                    : ($choice === $value);
            }

            return $choices;
        } else {
            return parent::transform($value);
        }
    }

    /**
     * Transforms a checkbox/radio button array to a single choice or an array
     * of choices.
     *
     * The input value is an array with the choices as keys and true/false as
     * values, depending on whether a given choice is selected. The output
     * is an array with the selected choices or a single selected choice.
     *
     * @param  mixed $value  An array if "expanded" or "multiple" is set to true,
     *                       a scalar value otherwise.
     * @return mixed $value  An array if "multiple" is set to true, a scalar
     *                       value otherwise.
     */
    protected function reverseTransform($value)
    {
        if ($this->getOption('expanded')) {
            $choices = array();

            foreach ($value as $choice => $selected) {
                if ($selected) {
                    $choices[] = $choice;
                }
            }

            if ($this->getOption('multiple')) {
                return $choices;
            } else {
                return count($choices) > 0 ? current($choices) : null;
            }
        } else {
            return parent::reverseTransform($value);
        }
    }
}
