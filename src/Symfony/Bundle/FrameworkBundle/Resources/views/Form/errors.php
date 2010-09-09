<?php if ($field->hasErrors()): ?>
    <ul>
        <?php foreach ($field->getErrors() as $error): ?>
            <li><?php echo $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
