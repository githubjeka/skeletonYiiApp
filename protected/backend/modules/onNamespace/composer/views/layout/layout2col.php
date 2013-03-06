<?php $this->beginContent('//layouts/main'); ?>
<div class="page secondary with-sidebar">
    <div class="page-header">
        <h1><?php echo Yii::t('ui', 'Menu') ?></h1>
    </div>

    <div class="page-sidebar">
        <ul>
            <li data-role="dropdown">
                <?php echo CHtml::link(Yii::t('ui', 'Modules')); ?>
                <ul class="sub-menu light sidebar-dropdown-menu open">
                    <li>
                        <?php echo CHtml::link(Yii::t('ui', 'List modules'), array('modules/list')); ?>
                    </li>
                    <li>
                        <?php echo CHtml::link(Yii::t('ui', 'Upload module'), array('modules/upload')); ?>
                    </li>
                    <li>
                        <?php echo CHtml::link(Yii::t('ui', 'Stored modules'), array('modules/stored')); ?>
                    </li>

                </ul>
            </li>
        </ul>
    </div>

    <div class="page-region">
        <div class="page-region-content">
            <?php echo $content; ?>
        </div>
    </div>
</div>
<?php $this->endContent(); ?>
