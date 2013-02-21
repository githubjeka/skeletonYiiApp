<?php
/**
 * Step 1.
 * Verification of claims
 * @var $this install\controllers\InstallController
 * @var $directories
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
$errors = false;
?>

<?php if (PHP_VERSION_ID < 50300): ?>
<h3 class="error-bar">
    Для установки нужен PHP 5.3+<br/>
    У вас установлен: <?php echo phpversion(); ?>
</h3>
<?php else: ?>

<h4>
    <strong class="fg-color-darken">1</strong>
    <small>→2→3</small>
</h4>

<h1><?php echo \Yii::t('install', 'Step 1. Verification of claims') ?></h1>

<div class="progress-bar">
    <div class="bar bg-color-pink" style="width: 30%"></div>

</div>

<div>
    <table>
        <tbody>
        <tr>
            <td>
                <?php echo \Yii::t('install', 'Magic Quotes Disabled'); ?>
                <br> magic_quotes_gpc = Off
            </td>
            <td>
                <?php if (!get_magic_quotes_gpc()) { ?>
                <span class="fg-color-green">OK</span>
                <?php } else { ?>
                <span class="fg-color-red">NO (Возможны проблемы при установке)</span>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="fg-color-pink">
                <?php echo \Yii::t('install', 'The following list of directories and files should be writable:'); ?>
            </td>
        </tr>
            <?php foreach ($directories as $path): ?>
        <tr>
            <td><?php echo $path ?></td>
            <td>
                <?php
                    CVarDumper::dump($this,10,true);
                    die();
                if ($this->getOwner()->isWritable($path)) {
                    echo '<span class="fg-color-green">OK</span>';
                } else {
                    $errors = true;
                    echo '<span class="fg-color-red">NO</span>';
                }
                ?>
            </td>
        </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <div class="row buttons">
        <?php if (!$errors): ?>
        <form action="" method="post">
            <input type="submit" name="next" value="<?php echo \Yii::t('install', 'Next'); ?>">
        </form>
        <?php else: ?>
        <div class="m20">
            <?php echo \Yii::t('install', 'Fix the errors'); ?>
        </div>
        <?php endif ?>
    </div>
</div>
<?php endif; ?>