<?php
namespace Install\controllers;

use Install\models\UploadForm;
use common\modules\Install\InstallModule;

/**
 * ModulesController file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class ModulesController extends \CController
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
                'actions' => array('list', 'upload', 'delete', 'stored', 'reinstall', 'DeleteFromStore'),
                'users' => array('@'),
            ),
            array('deny'),
        );
    }

    /**
     * Return php path for cmd
     * @return string
     */
    protected function getPhpPath()
    {
        $runningOnWindows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
        if ($runningOnWindows) {
            return 'php';
        } else {
            return '/usr/bin/php';
        }
    }

    /**
     * Return path to composer folder
     * @return string
     */
    public function getPathComposerFolder()
    {
        return \Yii::getPathOfAlias('common') . '/lib/composer/';
    }


    /**
     * Checks for file composer.json in composer folder
     * Makes changes to the composer.json file
     * Argument should be an array of the following structure:
     *<pre>
     * $additive = array(
     *    'require' => array(
     *       'nameModule' => "*"
     *     ),
     *     'repositories' => array(
     *          array(
     *              'type' => 'package',
     *              'package' => array(
     *              'name' => 'nameModule',
     *              'version' => 'version',
     *              'dist' => array(
     *                     'url' => 'pathtozip',
     *                     'type' => 'zip',
     *               )
     *          )
     *     )
     *    )
     * );
     * </pre>
     * more info https://getcomposer.org/doc/01-basic-usage.md
     * @param array $additive
     */
    private function addComposerConfig($additive = array())
    {
        if (file_exists($this->getPathComposerFolder() . 'composer.json')) {
            $composer = \CJSON::decode(file_get_contents($this->getPathComposerFolder() . 'composer.json'));
        } else {

            $composer = array(
                'config' => array(
                    'vendor-dir' => \Yii::app()->getModulePath(),
                    'cache-files-dir' => $this->getPathComposerFolder() . 'cache'
                ),
                'repositories' => array(array('packagist' => false)),
                'require' => array()
            );
        }

        if (array_key_exists('require', $composer)) {
            $composer['require'] = \CMap::mergeArray($composer['require'], $additive['require']);
        } else {
            $composer['require'] = $additive['require'];
        }

        $composer['repositories'] = \CMap::mergeArray($composer['repositories'], $additive['repositories']);
        $this->filePutComposer($composer);
    }

    /**
     * Json encode $newArrayconfig and put in composer.json
     * @param $newArrayConfig
     */
    protected function filePutComposer($newArrayConfig)
    {
        if (is_array($newArrayConfig)) {
            $newJson = \CJSON::encode($newArrayConfig);
            file_put_contents($this->getPathComposerFolder() . 'composer.json', $newJson);
        }
    }

    /**
     * Run in console composer update
     * more info https://getcomposer.org/doc/03-cli.md#update
     * @return array of string messages
     */
    protected function runComposer()
    {
        $currentFolder = getcwd();
        chdir($this->getPathComposerFolder());
        exec(($this->getPhpPath() . ' ' . ' composer.phar update 2>&1'), $out);
        chdir($currentFolder);

        return $out;
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
        $form = new UploadForm;
        $archive = \CUploadedFile::getInstance($form, 'archive');

        if (isset($archive) && $form->validate()) {

            $pathComposer = $this->getPathComposerFolder();

            if (file_exists($pathComposer . $archive)) {
                $form->addError('archive', \Yii::t('install', 'This module was previously loaded'));
            } else {

                $archive->saveAs($pathComposer . $archive);

                $fullNameModule = substr($archive->name, 0, -4);
                $fullNameModule = explode('-', $fullNameModule);

                $nameModule = trim($fullNameModule[0]);
                $version = '1.0.0';
                if (isset($fullNameModule[1]) and !empty($fullNameModule[1])) {
                    if (preg_match('/^[0-9]+[.]*[0-9]*[.]*[0-9]*$/i', $fullNameModule[1])) {
                        $version = $fullNameModule[1];
                    }
                }

                $additive = array(
                    'require' => array(
                        $nameModule => "*"
                    ),
                    'repositories' => array(
                        array(
                            'type' => 'package',
                            'package' => array(
                                'name' => $nameModule,
                                'version' => $version,
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
     * Render all list installed module
     * List is taken from the file (default: backend/module/offNamespace/) composer/installed.json
     */
    public function actionList()
    {
        if (file_exists($path = \Yii::app()->getModulePath() . '/composer/installed.json')) {
            $installed = file_get_contents($path);
            $modules = \CJSON::decode($installed);
            $this->render(
                'list',
                array(
                    'active' => 'home',
                    'modules' => $modules,
                )
            );
        } else {
            $this->render('list');
        }
    }

    /**
     * Delete Module
     * Delete from composer.json
     * Delete zip file from composer folder
     * If module has migrate that run down method in file migrate
     * @param null $id MigrateID
     */
    public function actionDelete($id = null)
    {
        if (ctype_digit($id) && (int)$id >= 0) {
            $pathComposer = $this->getPathComposerFolder();
            if (file_exists($this->getPathComposerFolder() . 'composer.json')) {
                $composerConfig = \CJSON::decode(file_get_contents($pathComposer . 'composer.json'));
                if (isset($composerConfig['repositories'][$id + 1])) {
                    $namePackage = $composerConfig['repositories'][$id + 1]['package']['name'];
                    $zipFile = $composerConfig['repositories'][$id + 1]['package']['dist']['url'];
                    $path = \Yii::getPathOfAlias('backend.modules.offNamespace.' . $namePackage . '.migrations');
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
//
                    array_splice($composerConfig['repositories'], $id + 1, 1);
                    unset($composerConfig['require'][$namePackage]);
                    if (empty($composerConfig['require'])) {
                        unset($composerConfig['require']);
                    }
                    $this->filePutComposer($composerConfig);
                    $this->runComposer();
                    if (file_exists($pathComposer . $zipFile)) {
                        unlink($pathComposer . $zipFile);
                    }
                }
            }
        }
        $this->redirect(\Yii::app()->createUrl('/install/modules'));
    }

    public function actionStored()
    {
        $cacheModules = glob($this->getPathComposerFolder() . '/cache/*', GLOB_ONLYDIR);
        if (isset($cacheModules)) {
            $list = array();
            foreach ($cacheModules as $pathModule) {
                $list[basename($pathModule)]['path'] = $pathModule;
                $list[basename($pathModule)]['version'] = glob($pathModule . '/*');
            }
            $cacheModules = $list;
        }

        $this->render('store', array('cacheModules' => $cacheModules));
    }

    public function actionReinstall()
    {

    }

    public function actionDeleteFromStore()
    {

    }
}