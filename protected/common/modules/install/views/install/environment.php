<?php
/**
 * Step 1.
 * Verification of claims
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
?>
<?php if (isset($hasErrors) && $hasErrors===false) : ?>
<h3 class="bg-color-green padding30">
    <?php echo Yii::t('install','Verification environment is successful'); ?>
</h3>
<?php endif; ?>