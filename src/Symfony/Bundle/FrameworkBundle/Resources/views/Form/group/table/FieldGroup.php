<?php echo $form->errors() ?>

<table>
    <?php foreach ($form as $field): ?>
        <?php if (!$field->isHidden()): ?>
            <?php echo $form->row($field) ?>
        <?php endif; ?>
    <?php endforeach; ?>
</table>

<?php echo $form->hidden() ?>
