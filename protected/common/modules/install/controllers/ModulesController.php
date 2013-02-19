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
    /**
     * @var string
     */
    public $layout = 'install.views.layout.layout2col';
    /**
     * @var string
     */
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

        var_dump(isset($archive));
        var_dump($form);
        var_dump($form->validate());

        if (isset($archive) && $form->validate()) {

            $fullNameModule = substr($archive->name, 0, -4);
            //Get name module
            if (preg_match('/[a-zA-Z]+/', $fullNameModule, $matches)) {

                $nameModule = $matches[0];
                //Get version module
                if (preg_match('/(__)[\w-\.]*$/', $fullNameModule, $matches)) {
                    $matches[0] = substr($matches[0], 2);
                    $versionModule = $this->normalizeVersion($matches[0]);
                } else {
                    $versionModule = $this->normalizeVersion(null);
                }
                //Get cache module and compare version
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
                    //create module
                    $additive = $this->createPackage($nameModule, $versionModule, $archive->name);

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
     * @param null $name
     * @param bool $redirect
     * @internal param null $id MigrateID
     * @return bool
     */
    public function actionDisable($name = null, $redirect = true)
    {
        if (isset($name)) {
            if ($composerConfig = $this->getFileComposer()) {
                if (isset($composerConfig['require'])) {
                    if (array_key_exists($name, $composerConfig['require'])) {
                        unset($composerConfig['require'][$name]);
                        if (empty($composerConfig['require'])) {
                            unset($composerConfig['require']);
                        }
                        $this->putFileComposer($composerConfig);
                    }

                    $path = \Yii::app()->getModulePath() . '/' . $name . '/migrations';
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

                    $this->runComposer();
                }
            }

            if ($redirect) {
                $this->redirect(\Yii::app()->createUrl('/install/modules'));
            }

            return true;
        }

        return false;

    }


    /**
     * Render page with stored modules
     */
    public function actionStored()
    {
        $this->render('store', array('cacheModules' => $this->getStoredModules()));
    }

    /**
     * Action, which is responsible for reinstalling module.
     * Module from the cache of the composer.
     * @param null $name
     * @param null $ver
     */
    public function actionReinstall($name = null, $ver = null)
    {

        if ($composerArr = $this->getFileComposer()) {

            $urlPackage = '/cache/' . $name . '/' . $ver . '-.zip';
            $package = $this->createPackage($name, $ver, $urlPackage);
            $composerArr = \CMap::mergeArray($composerArr, $package);
            $this->putFileComposer($composerArr);
            $this->deleteDuplicatesFromJsonFile();
            $this->runComposer();
        }

        $this->actionStored();
    }

    /**
     * Delete module from cache of the vendors composer.
     * Note: The module is not automatically disabled.
     * @param null $name
     * @param null $ver
     * @return bool
     */
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