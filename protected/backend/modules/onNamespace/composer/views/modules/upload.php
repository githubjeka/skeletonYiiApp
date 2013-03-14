<?php
/**
 * View module upload form
 * @var $this \CController
 * @var $form \CFormModel
 * @var $out array()
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

echo CHtml::beginForm('', 'post', array('enctype' => 'multipart/form-data')); ?>


<div class="bg-color-blueLight">
    <div class="page-region-content">
        <h3><?php echo Yii::t('install', 'Upload modules'); ?></h3>
        <label class="control-label"><?php echo Yii::t('ui', 'Archive modules'); ?></label>

        <div class="input-control">
            <?php
            echo CHtml::activeFileField($form, 'archive', array()); ?>
            <p class="help-block"><?php echo Yii::t('install', 'zip file extension'); ?></p>
        </div>

        <button type="submit"><?php echo Yii::t('install', 'Install'); ?></button>
    </div>
</div>



<?php $this->renderPartial('_status',array('form'=>$form,'out'=>$out)); ?>

<?php
echo CHtml::endForm();