<?php foreach ($group->getHiddenFields(true) as $field): ?>
    <?php echo $form->field($field->getKey()) ?>
<?php endforeach; ?>
