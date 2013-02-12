<div style="width: 380px;margin: auto">
    <?php
    /** @var $form CActiveForm */
    /** @var $this CController */
    /** @var $model users\models\User */

    $form = $this->beginWidget('CActiveForm');?>

    <?php echo CHtml::errorSummary($model); ?>

    <div class="input-control text span5">
        <?php echo $form->label($model, 'username');?>
        <?php echo $form->textField($model, 'username', array('required' => true));?>
    </div>
    <div class="input-control text span5">
        <?php echo $form->label($model, 'password');?>
        <?php echo $form->passwordField($model, 'password', array('required' => true));?>
    </div>
    <label class="input-control checkbox">
        <?php echo $form->checkBox($model, 'rememberUser');?>
        <span><?php echo $form->label($model, 'rememberUser');?></span>
    </label>


    <input type="submit" class="button">

    <?php $this->endWidget(); ?>

    <?php
    if (Yii::app()->homeUrl==='/index.php')    {
        echo CHtml::link(Yii::t('users','Registration'),array('/users/register'));
    }
?>
</div>