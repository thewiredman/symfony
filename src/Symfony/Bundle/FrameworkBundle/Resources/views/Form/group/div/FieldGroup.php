<?php echo $form->errors() ?>

<div>
    <?php foreach ($form as $name => $field): ?>
        <?php echo $form->row($name) ?>
    <?php endforeach; ?>
</div>
