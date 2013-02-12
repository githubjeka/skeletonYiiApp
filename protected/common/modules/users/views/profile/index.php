<?php
/**
 * @var $profile UserProfile
 * @var $user User
 * @var $form CActiveForm
 * @var $changePasswordForm ChangePasswordForm
 */
?>

<div class="grid">
    <div class="row">
        <h1><?php echo Yii::t('users', 'Personal profile'); ?></h1>

        <div class="span6">

            <fieldset>
                <legend><?php echo Yii::t('users', 'Change password'); ?></legend>
                <?php $form = $this->beginWidget('CActiveForm'); ?>

                <?php echo $form->errorSummary($user); ?>

                <div class="input-control text">
                    <?php echo $form->label($user, 'password'); ?>
                    <?php echo $form->passwordField($user, 'password', array('required' => true)) ?>
                </div>

                <div class="input-control text">
                    <?php echo $form->label($user, 'newPassword'); ?>
                    <?php echo $form->passwordField($user, 'newPassword', array('required' => true)) ?>
                </div>

                <div class="input-control text">
                    <?php echo $form->label($user, 'confirmPassword'); ?>
                    <?php echo $form->passwordField($user, 'confirmPassword', array('required' => true)) ?>
                </div>

                <?php echo \CHtml::submitButton(Yii::t('users',Yii::t('ui','Change'))); ?>


                <?php $this->endWidget(); ?>
        </div>
        <!-- form -->
        </fieldset>
    </div>
</div>
