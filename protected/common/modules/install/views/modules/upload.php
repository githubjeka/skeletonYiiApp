<?php
/**
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

echo CHtml::form('', 'post', array('enctype' => 'multipart/form-data')); ?>

<fieldset>
    <legend><?php echo Yii::t('ui','Upload modules'); ?></legend>
    <div class="control-group">
        <label class="control-label"><?php echo Yii::t('ui','Archive modules'); ?></label>

        <div class="controls">
            <?php echo CHtml::activeFileField($form, 'archive'); ?>
            <p class="help-block"><?php echo Yii::t('ui','zip file extension'); ?></p>
        </div>
    </div>


    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?php echo Yii::t('ui','Install'); ?></button>
        <!--button class="btn">Cancel</button-->
    </div>
</fieldset>

<?php if ($form->hasErrors() or isset($out)) { ?>
<fieldset>
    <legend>Состояние</legend>
    <?php
    echo $form->getError('archive');
    if (isset($out)) {
        foreach ($out as $ms) {
            echo '<div>' . trim($ms, '[32m[0') . '</div>';
        }
    }
    ?>
</fieldset>
<?php } ?>

</form>