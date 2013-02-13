<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <!-- Place favicon.ico and apple-touch-icon.png in the theme directory -->
    <?php
    $clientScript = Yii::app()->clientScript;
    $clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/modern.css');
    $clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/modern-responsive.css');
    $clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/theme.css');
    $clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/modernizr-2.6.2.min.js');
    $clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/metroJs/input-control.js', CClientScript::POS_END);
    $clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/metroJs/dropdown.js', CClientScript::POS_END);
    $clientScript->registerCoreScript('jquery', CClientScript::POS_HEAD);
    /*
    $clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/plugins.js', CClientScript::POS_END);
    $clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/user.js', CClientScript::POS_END);
    */
    /*
     <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
     $clientScript->registerScriptFile(
        "var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'))",
        CClientScript::POS_END
    );*/
    ?>
    <link rel="icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/favicon.ico" type="image/x-icon"/>
</head>
<body class="modern-ui">

<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to
    improve your experience.</p>
<![endif]-->

<div class="page">
    <div class="page-header">
        <nav class="nav-bar">
            <div class="nav-bar-inner bg-color-grayDark padding10">
                <a href="<?php echo Yii::app()->createUrl('/'); ?>">
                    <span class="element brand">
                            <?php echo Yii::app()->name ?>
                    </span>
                </a>

                <span class="divider"></span>

                <ul class="menu">
                    <?php if (!Yii::app()->user->isGuest) { ?>
                    <li data-role="dropdown">
                        <a><?php echo Yii::t('ui', 'Menu'); ?></a>
                        <?php $this->widget(
                        'zii.widgets.CMenu',
                        array(
                            'htmlOptions' => array('class' => 'dropdown-menu'),
                            'activeCssClass' => 'active',
                            'items' => array(
                                array('label' => Yii::t('ui', 'Options'), 'url' => array('/install/config')),
                            ),
                        )
                    ); ?>
                    </li>
                    <?php } ?>
                    <li data-role="dropdown">
                        <a>User</a>
                        <?php $this->widget(
                        'zii.widgets.CMenu',
                        array(
                            'htmlOptions' => array('class' => 'dropdown-menu'),
                            'activeCssClass' => 'active',
                            'items' => array(
                                array(
                                    'label' => 'Account',
                                    'url' => array('/users/profile'),
                                    'visible' => !Yii::app()->user->isGuest
                                ),
                                array(
                                    'label' => 'List users',
                                    'url' => array('/users/admin'),
                                    'visible' => !Yii::app()->user->isGuest
                                ),
                                array(
                                    'label' => 'Login',
                                    'url' => array('/users'),
                                    'visible' => Yii::app()->user->isGuest
                                ),
                                array(
                                    'label' => 'Logout (' . Yii::app()->user->name . ')',
                                    'url' => array('/site/logout'),
                                    'visible' => !Yii::app()->user->isGuest
                                )
                            ),
                        )
                    ); ?>
                    </li>
                </ul>

                 <?php echo CHtml::link('<span class="element place-right">'.Yii::t('ui', 'Show site').' </span>', '/'); ?>
            </div>
        </nav>

        <?php if (($messages = Yii::app()->user->getFlash('messages'))): ?>
        <div>
            <div class="notices">
                <div class="bg-color-green">
                    <a href="#" class="close"></a>

                    <div class="notice-header fg-color-white">Access granted!</div>
                    <div class="notice-text"><?php
                        if (is_array($messages)) {
                            echo implode('<br>', $messages);
                        } else {
                            echo $messages;
                        }
                        ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="page-region">
        <div class="page-region-content">
            <?php echo $content; ?>
        </div>
    </div>

    <footer>

    </footer>
</div>

</body>
</html>
