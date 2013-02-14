<?php
/**
 * cache.php file.
 * view list modules of cache
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

if (isset($cacheModules)) {
    ?>
<div>
    <p>
        <?php echo Yii::t('install', 'Stored modules'); ?>
    </p>

    <ul class="accordion fg-color-darken dark" data-role="accordion">
        <?php
        foreach ($cacheModules as $nameModule=>$versionsModule) {

            echo CHtml::openTag('li');

                echo CHtml::link(CHtml::encode($nameModule));

                foreach ($versionsModule as $version) {
                    echo CHtml::openTag('div');
                    echo CHtml::encode($version);
                    echo CHtml::link(
                        Yii::t('install', 'Reinstall'),
                        array('/install/modules/reinstall','name'=>$nameModule,'ver'=>$version),
                        array('class' => 'place-right')
                    );
                    echo CHtml::link(
                        Yii::t('install', 'Delete'),
                        array('/install/modules/delete','name'=>$nameModule,'ver'=>$version),
                        array('class' => 'fg-color-red place-right')
                    );
                    echo CHtml::closeTag('div');
                }

            echo CHtml::closeTag('li');

            // register accordion.js
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->theme->baseUrl . '/js/metroJs/accordion.js',
                CClientScript::POS_END
            );
            ;

        }
        ?>
    </ul>

</div>
<?php
}