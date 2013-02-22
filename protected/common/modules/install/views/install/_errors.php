<?php
/**
 * @var $errors array
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
?>
<?php if (isset($hasErrors) && $hasErrors===true) : ?>

<h3 class="small"><?php echo Yii::t('install', 'Errors');?></h3>

<header class="page-header bg-color-red body-text padding20">
    <?php
    foreach ($errors as $error) {
        echo CHtml::tag('h4', array(), $error);
    }
    ?>
</header>

<?php endif; ?>