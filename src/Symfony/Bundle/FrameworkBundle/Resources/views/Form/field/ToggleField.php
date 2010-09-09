<?php
    echo $view->render('FrameworkBundle:Form:field/InputField', array(
        'form'       => $form,
        'group'      => $group,
        'field'      => $field,
        'attributes' => $attributes,
    ))
?>

<?php if ($label = $field->getOption('label')): ?>
    <?php echo $form->generator->contentTag('label', $label, array('for' => $field->getId())) ?>
<?php endif; ?>
