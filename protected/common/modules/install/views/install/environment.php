<?php
/**
 * Step 1.
 * Verification of claims
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
?>
<?php if ($valid===true) : ?>
<h3 class="bg-color-green padding30">
    <?php echo Yii::t('install','Verification environment is successful'); ?>
</h3>
<?php endif; ?>