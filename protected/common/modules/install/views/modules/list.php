<?php
/**
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
?>
<h2>
    <?php echo Yii::t('ui','List of installed modules'); ?>
    <?php echo CHtml::link(Yii::t('ui','Add module'), array('/install/modules/upload')); ?>
</h2>


<table class="table table-striped">
    <thead>
    <tr>
        <th>Modules</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($modules)) { ?>
        <?php foreach ($modules as $key => $module): ?>
        <tr>
            <td>
                <h3><?php  echo CHtml::link($module['name'], array('/' . $module['name'])); ?></h3>

                <p><b><?php echo $module['version_normalized'] ?></b></p>
            </td>
            <td style="width: 250px;">
                <?php
//                echo CHtml::link(
//                    Yii::t('install', 'Disable'),
//                    array('/install/modules/disable','id'=>$key),
//                    array('class' => 'btn btn-primary')
//                );
                ?>
                <?php
                echo CHtml::link(
                    Yii::t('install', 'Delete'),
                    array('/install/modules/delete','id'=>$key),
                    array('class' => 'btn btn-primary')
                );
                ?>
            </td>
        </tr>
            <?php endforeach;
    }
    ?>
    </tbody>
</table>
<?php
// (isset($modules))  ?  dump($modules) : '' ;
?>