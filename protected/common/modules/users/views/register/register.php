<h1 class="has_background"><?php echo Yii::t('users','Registration'); ?></h1>

<div class="login_box rc5">
	<div class="form wide">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'user-register-form',
			'enableAjaxValidation'=>false,
		)); ?>

		<?php echo $form->errorSummary(array($user)); ?>

		<div class="row">
			<?php echo $form->labelEx($user,'username'); ?>
			<?php echo $form->textField($user,'username'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($user,'newPassword'); ?>
			<?php echo $form->passwordField($user,'newPassword',array('required' => true)); ?>
		</div>

        <div class="row">
			<?php echo $form->labelEx($user,'confirmPassword'); ?>
			<?php echo $form->passwordField($user,'confirmPassword',array('required' => true)); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton(Yii::t('ui', 'Submit')); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::link(Yii::t('users', 'Login'), array('/users')) ?><br>
			<?php echo CHtml::link(Yii::t('users', 'Remind Password'), array('/users/remind')) ?>
		</div>

		<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>
