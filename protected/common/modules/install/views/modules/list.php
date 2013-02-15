<?php
/**
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
?>
<h2>
    <?php echo Yii::t('ui', 'List of installed modules'); ?>
    <?php echo CHtml::link(Yii::t('ui', 'Add module'), array('/install/modules/upload')); ?>
</h2>


<table class="striped">
    <thead>
    <tr>
        <th>Modules</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (is_array($modules)) {
        foreach ($modules as $key => $module): ?>
        <tr>
            <td>
                <h3><?php  echo CHtml::link($module['name'], array('/' . $module['name'])); ?></h3>

                <p><b><?php echo $module['version_normalized'] ?></b></p>
            </td>
            <td>
                <?php
                echo CHtml::link(
                    Yii::t('install', 'Disable'),
                    array('/install/modules/disable', 'name' => $module['name']),
                    array('class' => 'btn btn-primary')
                );
                ?>
            </td>
        </tr>
            <?php
        endforeach;
    }
    ?>
    </tbody>
</table>