<?php
namespace Install\controllers;

use Install\models\UploadForm;
use Install\extensions\helpers\ComposerHelper;
use common\modules\Install\InstallModule;

/**
 * ModulesController file.
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class ModulesController extends ComposerHelper
{
    public $layout = 'install.views.layout.layout2col';
    public $defaultAction = 'list';

    /**
     * @return array list of action filters (See CController::filter)
     */
    public function filters()
    {
        return array('accessControl');
    }

    /**
     * @return array rules for the "accessControl" filter.
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions' => array('list', 'upload', 'disable', 'stored', 'reinstall', 'delete'),
                'users' => array('@'),
            ),
            array('deny'),
        );
    }

    /**
     * Render all list installed module
     */
    public function actionList()
    {
        $this->render(
            'list',
            array(
                'modules' => ($modules = $this->getInstalledModules()) ? $modules : array(),
            )
        );
    }

    /**
     * Upload zip module file from form
     * Move its to composer folder (common/lib/composer)
     * Add $additive to composer.json
     * Run composer.phar update
     * if module has migrate folder that applies migration
     */
    public function actionUpload()
    {
        $form = new UploadForm('uploadModule');
        $archive = \CUploadedFile::getInstance($form, 'archive');

        if (isset($archive) && $form->validate()) {

            $fullNameModule = substr($archive->name, 0, -4);

            if (preg_match('/[a-zA-Z]+/', $fullNameModule, $matches)) {
                $nameModule = $matches[0];
                if (preg_match('/(__)[\w-\.]*$/', $fullNameModule, $matches)) {
                    $matches[0] = substr($matches[0], 2);
                    $versionModule = $this->normalizeVersion($matches[0]);
                } else {
                    $versionModule = $this->normalizeVersion(null);
                }

                if ($storedModules = $this->getStoredModules()) {

                    foreach ($storedModules as $name => $versions) {
                        if ($name == $nameModule) {
                            foreach ($versions as $version) {
                                if ($version == $versionModule) {
                                    $form->addError('archive', \Yii::t('install', 'This module was previously loaded'));
                                }
                            }
                        }
                    }
                }
                if (!$form->hasErrors()) {

                    $pathComposer = $this->getPathComposerFolder();
                    $archive->saveAs($pathComposer . $archive);

                    $additive = array(
                        'require' => array(
                            $nameModule => $versionModule
                        ),
                        'repositories' => array(
                            array(
                                'type' => 'package',
                                'package' => array(
                                    'name' => $nameModule,
                                    'version' => $versionModule,
                                    'dist' => array(
                                        'url' => $archive->name,
                                        'type' => 'zip',
                                    ),
                                ),
                            )
                        )
                    );

                    $this->addComposerConfig($additive);
                    $logComposer = $this->runComposer();

                    unlink($this->getPathComposerFolder() . $archive);

                    //TODO use Yii::app->getModulePath()
                    $migrationPath = 'backend.modules.' . $nameModule . '.migrations';
                    if (is_dir(\Yii::getPathOfAlias($migrationPath))) {
                        $logMigration = InstallModule::wrapperConsoleRun(
                            array('\Yiic', 'migrate', 'up', '--interactive=0', '--migrationPath=' . $migrationPath)
                        );
                        $logMigration = explode("\n", $logMigration);

                        $log = \CMap::mergeArray($logComposer, $logMigration);
                    } else {
                        $log = $logComposer;
                    }

                }


            } else {
                $form->addError('archive', \Yii::t('install', 'Module name is not correct'));
            }
        }

        $this->render(
            'upload',
            array(
                'form' => $form,
                'out' => (isset($log)) ? $log : null,
            )
        );
    }


    /**
     * Disable Module
     * Disable from composer.json
     * Delete zip file from composer folder
     * If module has migrate that run down method in file migrate
     * @param null $id MigrateID
     * @param bool $redirect
     * @return bool
     */
    public function actionDisable($id = null, $redirect = true)
    {
        if (ctype_digit($id) || is_int($id) && (int)$id >= 0) {
            $pathComposer = $this->getPathComposerFolder();
            if (file_exists($this->getPathComposerFolder() . 'composer.json')) {
                $composerConfig = \CJSON::decode(file_get_contents($pathComposer . 'composer.json'));
                if (isset($composerConfig['repositories'][$id + 1])) {
                    $namePackage = $composerConfig['repositories'][$id + 1]['package']['name'];
                    $zipFile = $composerConfig['repositories'][$id + 1]['package']['dist']['url'];
                    $path = \Yii::app()->getModulePath() . 'offNamespace/' . $namePackage . '/migrations';
                    if (is_dir($path)) {
                        $files = glob($path . '/m[0-9_]*.php', GLOB_BRACE);
                        krsort($files);
                        foreach ($files as $file) {
                            require_once($file);
                            $class = basename($file, ".php");
                            if (class_exists($class)) {
                                /** @var $migration \CDbMigration should be parent */
                                $migration = new $class;
                                ob_start();
                                $migration->down();
                                ob_get_clean();
                                $db = \Yii::app()->getDb();
                                $db->createCommand()->delete(
                                    $db->tablePrefix . 'tbl_migration',
                                    '`version`="' . $class . '"'
                                );
                            }
                        }
                    }
                    array_splice($composerConfig['repositories'], $id + 1, 1);
                    unset($composerConfig['require'][$namePackage]);
                    if (empty($composerConfig['require'])) {
                        unset($composerConfig['require']);
                    }
                    $this->putFileComposer($composerConfig);
                    $this->runComposer();
                    if (file_exists($pathComposer . $zipFile)) {
                        unlink($pathComposer . $zipFile);
                    }
                }
            }

            if ($redirect) {
                $this->redirect(\Yii::app()->createUrl('/install/modules'));
            }

            return true;
        }

        return false;

    }


    public function actionStored()
    {
        $this->render('store', array('cacheModules' => $this->getStoredModules()));
    }

    public function actionReinstall($name = null, $ver = null)
    {
        if ($composerArr = $this->getFileComposer()) {
            if (isset($composerArr['require'])) {
                foreach ($composerArr['require'] as $n => &$v) {
                    if ($name == $n) {
                        $v = $ver;
                        break;
                    }
                }
            } else {
                $composerArr['require'] = array($name => $ver);
            }
//            foreach ($composerArr['repositories'] as $id => $repository) {
//                if (isset($repository['package']['version']) && isset($repository['package']['name'])) {
//                    if ($repository['package']['name'] == $name && $repository['package']['version'] = $ver) {
//                        $repository[$id]=array();
//                    }
//                }
//            }
            $this->putFileComposer($composerArr);
            $this->runComposer();
        }

        $this->actionStored();
    }

    public function actionDelete($name = null, $ver = null)
    {
        if (!empty($name) && !empty($ver)) {
            if (is_dir($folderPath = $this->getPathComposerFolder() . '/cache/' . $name)) {
                if (is_file($file = $folderPath . '/' . $ver . '-.zip')) {
                    if ($installedModules = $this->getInstalledModules()) {
                        foreach ($installedModules as $id => $data) {
                            if ($data['version_normalized'] == $ver) {
                                if ($this->actionDisable((int)$id, false) == false) {
                                    return false;
                                }
                            }
                        }
                    }
                    unlink($file);
                    if (!glob($folderPath . '/*')) {
                        rmdir($folderPath);
                    }
                }
            }
        }

        $this->actionStored();
        return true;
    }
}