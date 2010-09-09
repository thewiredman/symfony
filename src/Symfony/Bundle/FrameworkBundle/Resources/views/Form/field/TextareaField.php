<?php $view->extend('FrameworkBundle:Form:field/base') ?>

<?php echo $form->generator->contentTag('textarea', $view->escape($field->getDisplayedData()), $attributes) ?>
