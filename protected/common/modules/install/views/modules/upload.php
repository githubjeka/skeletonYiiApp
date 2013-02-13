<?php
/**
 * View module upload form
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

echo CHtml::beginForm('', 'post', array('enctype' => 'multipart/form-data')); ?>


<div class="bg-color-blueLight">
    <div class="page-region-content">
        <h3><?php echo Yii::t('install', 'Upload modules'); ?></h3>
        <label class="control-label"><?php echo Yii::t('ui', 'Archive modules'); ?></label>

        <div class="input-control">
            <?php /** @var $form \CFormModel */
            echo CHtml::activeFileField($form, 'archive', array()); ?>
            <p class="help-block"><?php echo Yii::t('install', 'zip file extension'); ?></p>
        </div>

        <button type="submit"><?php echo Yii::t('install', 'Install'); ?></button>
    </div>
</div>



<?php if ($form->hasErrors() or isset($out)) { ?>
<div>
    <p>Состояние</p>
    <?php
    echo $form->getError('archive');
    if (isset($out)) {
        foreach ($out as $ms) {
            echo '<div>' . trim($ms, '[32m[0') . '</div>';
        }
    }
    ?>
</div>
<?php } ?>

<?php
echo CHtml::endForm();