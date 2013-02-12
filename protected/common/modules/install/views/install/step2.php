<?php

/**
 * @var $form CActiveForm
 */
?>

<h4>
    <small>1→</small><strong class="fg-color-blueLight">2</strong><small>→3</small>
</h4>

<h1><?php echo Yii::t('install', 'Step 2. DB connection') ?></h1>
<div class="progress-bar">
    <div class="bar bg-color-pink" style="width: 30%"></div>
    <div class="bar bg-color-yellow" style="width: 30%"></div>
</div>
<div class="line"></div>

<div class="form wide">
    <?php $form = $this->beginWidget('CActiveForm'); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="input-control text row">
        <?php echo $form->label($model, 'dbHost'); ?>
        <?php echo $form->textField($model, 'dbHost',array('required'=>true)) ?>
    </div>

    <div class="input-control text row">
        <?php echo $form->label($model, 'dbName'); ?>
        <?php echo $form->textField($model, 'dbName',array('required'=>true)) ?>
    </div>

    <div class="input-control text row">
        <?php echo $form->label($model, 'dbUserName'); ?>
        <?php echo $form->textField($model, 'dbUserName',array('required'=>true)) ?>
    </div>

    <div class="input-control password row">
        <?php echo $form->label($model, 'dbPassword'); ?>
        <?php echo $form->passwordField($model, 'dbPassword') ?>
    </div>

    <div class="row buttons">
        <input type="submit" name="next" value="<?php echo Yii::t('install', 'Connect') ?>">
    </div>

    <?php $this->endWidget(); ?>
</div>