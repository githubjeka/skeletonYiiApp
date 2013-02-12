<h1><?php echo Yii::t('install','Installation complete')?></h1>
<div class="progress-bar">
    <div class="bar bg-color-pink" style="width: 30%"></div>
    <div class="bar bg-color-yellow" style="width: 30%"></div>
    <div class="bar bg-color-green" style="width: 40%"></div>
</div>
<div class="body-text">
    <p><?php echo Yii::t('install','For quick start use these administrator credentials')?></p>
    <p><?php echo Yii::t('install','Strongly recommended to change them')?></p>
    <p class="indent"><?php echo Yii::t('install','Username'); ?>: <strong>admin</strong></p>
    <p class="indent"><?php echo Yii::t('install','Password'); ?>: <strong>adminсms</strong></p>
</div>
<?php
echo CHtml::link(Yii::t('install','Sign in'),Yii::app()->createUrl('/users'));
?>