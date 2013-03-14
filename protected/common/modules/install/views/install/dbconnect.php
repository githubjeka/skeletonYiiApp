<?php

/**
 * @var $form CActiveForm
 */
?>
<?php if (($valid)==false) { ?>
<div class="form wide">
    <?php $form = $this->beginWidget('CActiveForm'); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="input-control text row">
        <?php echo $form->label($model, 'dbHost'); ?>
        <?php echo $form->textField($model, 'dbHost', array('required' => true)) ?>
    </div>

    <div class="input-control text row">
        <?php echo $form->label($model, 'dbName'); ?>
        <?php echo $form->textField($model, 'dbName', array('required' => true)) ?>
    </div>

    <div class="input-control text row">
        <?php echo $form->label($model, 'dbUserName'); ?>
        <?php echo $form->textField($model, 'dbUserName', array('required' => true)) ?>
    </div>

    <div class="input-control password row">
        <?php echo $form->label($model, 'dbPassword'); ?>
        <?php echo $form->passwordField($model, 'dbPassword') ?>
    </div>

    <div class="row buttons">
        <input type="submit" name="connect" value="<?php echo Yii::t('install', 'Connect') ?>">
    </div>

    <?php $this->endWidget(); ?>
</div>
<?php } else { ?>
<h3 class="bg-color-green padding30">
    <?php echo Yii::t('install','DB connection is successful'); ?>
</h3>
<?php } ?>