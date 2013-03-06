<?php
/**
 * View status after run composer install
 * @var $form \CFormModel
 * @var $out array()
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

if ($form->hasErrors() || isset($out)) { ?>
<div>
    <p><?php echo Yii::t('install', 'Status'); ?></p>
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