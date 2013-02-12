<?php $this->beginContent('//layouts/main'); ?>
<div class="page secondary with-sidebar">
    <div class="page-header">
        <h1><?php echo Yii::t('ui','Menu') ?></h1>
    </div>

    <div class="page-sidebar">
        <ul>
            <li>
                <i class="icon-properties"></i>
                <?php echo CHtml::link(Yii::t('ui','Options'),array('/install/config')); ?>
            </li>
            <li>
                <i class="icon-upload"></i>
                <?php echo CHtml::link(Yii::t('ui','Modules'),array('/install/modules')); ?>
            </li>
        </ul>
    </div>

    <div class="page-region">
        <?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>