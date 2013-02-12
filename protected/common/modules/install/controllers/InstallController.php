<?php
namespace Install\controllers;

use Install\models\InstallConfigureForm;
use \common\modules\Install\InstallModule;

/**
 * InstallController file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class InstallController extends \CController
{
    public $defaultAction = 'step1';
    public $layout = 'install.views.layout.install';

    public function actionStep1()
    {
        InstallModule::isInstall();

        if (isset($_POST['next'])) {
            $this->redirect($this->createUrl('step2'));
        }
        $this->render('step1');
    }

    public function actionStep2()
    {
        InstallModule::isInstall();
        $model = new InstallConfigureForm;

        if (isset($_POST['next']) && isset($_POST['Install\models\InstallConfigureForm'])) {
            $model->attributes = $_POST['Install\models\InstallConfigureForm'];
            if ($model->validate()) {
                $model->install();
                $this->redirect($this->createUrl('step3'));
            }
        }

        $this->render('step2', array('model' => $model,));
    }

    public function actionStep3()
    {
        $model = new \Install\models\InstallFinishForm();
        $model->install();

        $this->render(
            'step3',
            array(
                'model' => $model,
            )
        );
    }

    protected function getDirectories()
    {
        $root = \Yii::getPathOfAlias('root');
        $directories = \SplFixedArray::fromArray(
            array(
                $root . '/frontend/www/assets',
                $root . '/backend/www/assets',
                $root . '/frontend/runtime',
                $root . '/backend/runtime',
                $root . '/console/runtime'
            )
        );
        $this->removeTempDir($directories);
        $this->createTempDir($directories);
        foreach ($directories as $key => $value) {
            $directories[$key] = realpath($value);
        }
        return $directories;
    }

    /**
     * remove temp directories such as runtime and assets
     * @param array $paths
     * @return mixed
     */
    protected function removeTempDir($paths = array())
    {
        foreach ($paths as $path) {
            $this->rrmdir($path);
        }
        return true;
    }

    protected function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        unlink(
                            $dir . "/" . $object
                        );
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * Creates a directory if it doesn't exists
     * @param array $paths
     */
    function createTempDir($paths = array())
    {
        foreach ($paths as $path) {
            if (!is_dir($path)) {
                if (mkdir($path)) {
                    chmod($path, 02777);
                }
            }
        }
    }

    public function actionCompleted()
    {
        $this->render('completed');
    }

    /**
     * Check if path is writeable
     * @param $path
     * @return bool
     */
    public function isWritable($path)
    {
        return is_writable($path);
    }

}
