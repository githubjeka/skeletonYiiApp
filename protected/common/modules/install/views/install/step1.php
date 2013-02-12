<?php
/**
 * Step 1.
 * Verification of claims
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
$errors = false;
?>

<?php if (PHP_VERSION_ID < 50300): ?>
<h3>
    Для установки нужен PHP 5.3<br/>
    У вас установлен: <?php echo phpversion(); ?>
</h3>
<?php else: ?>


<h4>
    <strong class="fg-color-blueLight">1</strong>
    <small>→2→3</small>
</h4>

<h1><?php echo \Yii::t('install', 'Step 1. Verification of claims') ?></h1>
<div class="progress-bar">
    <div class="bar bg-color-pink" style="width: 30%"></div>

</div>
<div class="line"></div>

<div class="form">

    <table cellpadding="3" cellspacing="3">
        <tr>
            <td width="300px">
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
    </table>

    <div class="m20">
        <?php echo \Yii::t('install', 'The following list of directories and files should be writable:'); ?>
    </div>
    <table cellpadding="3" cellspacing="3">
        <?php foreach ($this->getDirectories() as $path): ?>
        <tr>
            <td width="300px"><?php echo $path ?></td>
            <td>
                <?php
                $result = $this->isWritable($path);
                if ($result) {
                    echo '<span class="fg-color-green">OK</span>';
                } else {
                    $errors = true;
                    echo '<span class="fg-color-red">NO</span>';
                }
                ?>
            </td>
        </tr>
        <?php endforeach ?>
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