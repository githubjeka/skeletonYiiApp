<div class="body-text">
    <p><?php echo Yii::t('install','For quick start use these administrator credentials')?></p>
    <p><?php echo Yii::t('install','Strongly recommended to change them')?></p>
    <p class="indent"><?php echo Yii::t('install','Username'); ?>: <strong>admin</strong></p>
    <p class="indent"><?php echo Yii::t('install','Password'); ?>: <strong>adminсms</strong></p>
</div>
<?php
echo CHtml::link(Yii::t('install','Sign in'),Yii::app()->createUrl('/users'));
?>