<?php
/**
 *
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yiic message' command.
 *
 *  @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

$root = dirname(__FILE__).DIRECTORY_SEPARATOR.'../../';
$backend = $root . '/backend/';
$modules = $root .'/common/modules/';

return array(
    'sourcePath'=>$backend,
    'messagePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'messages',
    'languages'=>array('ru'),
    'fileTypes'=>array('php'),
    'overwrite'=>true,
    'exclude'=>array(
        '.svn',
        '.gitignore',
        'yiilite.php',
        'yiit.php',
        '/i18n/data',
        '/messages',
        '/vendors',
        '/web/js',
    ),
);