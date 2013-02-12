<?php
/**
 * Main layout frontend app
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

$themePath = Yii::app()->theme->baseUrl;
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="<?php echo $themePath; ?>/images/favicon.ico">

    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="stylesheet" href="<?php echo $themePath; ?>/css/normalize.css">
    <link rel="stylesheet" href="<?php echo $themePath; ?>/css/main.css">
    <script src="<?php echo $themePath; ?>/js/vendor/modernizr-2.6.2.min.js"></script>
</head>

<body>
<div class="container" id="page">

    <header id="header">
        <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
    </header>
    <!-- header -->

    <nav id="mainmenu">
        <?php $this->widget(
        'zii.widgets.CMenu',
        array(
            'items' => array(
                array('label' => 'Home', 'url' => array('/site')),
                array('label' => 'Admin panel', 'url' => Yii::app()->getBaseUrl() . '/backend.php'),
                array('label' => 'Login', 'url' => array('/users'), 'visible' => Yii::app()->user->isGuest),
                array(
                    'label' => 'Logout (' . Yii::app()->user->name . ')',
                    'url' => array('/site/logout'),
                    'visible' => !Yii::app()->user->isGuest
                )
            ),
        )
    ); ?>
    </nav>
    <!-- mainmenu -->

    <?php if (isset($this->breadcrumbs)): ?>
        <?php $this->widget(
        'zii.widgets.CBreadcrumbs',
        array(
            'links' => $this->breadcrumbs,
        )
    ); ?><!-- breadcrumbs -->
    <?php endif ?>

    <?php echo $content; ?>

    <div class="clearfix"></div>

    <footer id="footer">
        Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
        All Rights Reserved.<br/>
        <?php echo Yii::powered(); ?>
    </footer>
    <!-- footer -->

</div>
<!-- page -->

</body>
</html>