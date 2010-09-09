<?php $view->extend('FrameworkBundle:Form:field/base') ?>

<?php if ($field->getOption('expanded')): ?>
    <?php $f = $form->create($field); foreach ($field as $child): ?>
        <?php echo $f->field($child) ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php
    function renderChoices($form, $field, array $choices, array $selected)
    {
        $options = array();

        foreach ($choices as $key => $option) {
            if (is_array($option)) {
                $options[] = $this->generator->contentTag(
                    'optgroup',
                    "\n".renderChoices($form, $field, $option, $selected)."\n",
                    array('label' => $form->generator->escape($key))
                );
            } else {
                $attributes = array('value' => $form->generator->escape($key));

                if (isset($selected[strval($key)])) {
                    $attributes['selected'] = true;
                }

                $options[] = $form->generator->contentTag(
                    'option',
                    $form->generator->escape($option),
                    $attributes
                );
            }
        }

        return implode("\n", $options);
    }


    $attrs['id'] = $field->getId();
    $attrs['name'] = $field->getName();
    $attrs['disabled'] = $field->isDisabled();

    // Add "[]" to the name in case a select tag with multiple options is
    // displayed. Otherwise only one of the selected options is sent in the
    // POST request.
    if ($field->getOption('multiple') && !$field->getOption('expanded')) {
        $attrs['name'] .= '[]';
    }

    if ($field->getOption('multiple')) {
        $attrs['multiple'] = 'multiple';
    }

    $selected = array_flip(array_map('strval', (array)$field->getDisplayedData()));
    $html = "\n";

    if (!$field->isRequired()) {
        $html .= renderChoices($form, $field, array('' => $field->getOption('empty_value')), $selected)."\n";
    }

    $choices = $field->getOption('choices');

    if (count($field->getPreferredChoices()) > 0) {
        $html .= renderChoices($form, $field, array_intersect_key($choices, $field->getPreferredChoices()), $selected)."\n";
        $html .= $field->generator->contentTag('option', $field->getOption('separator'), array('disabled' => true))."\n";
    }

    $html .= renderChoices($form, $field, array_diff_key($choices, $field->getPreferredChoices()), $selected)."\n";

    echo $form->generator->contentTag('select', $html, array_merge($attrs, $attributes));
    ?>
<?php endif; ?>
