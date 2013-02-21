<?php

use common\modules\Install\InstallModule;
use \Install\extensions\helpers\AbstractStep;

/**
 * Environment.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

class Environment extends CBehavior implements AbstractStep
{
    public function onStep()
    {
        InstallModule::isInstall();

        if (isset($_POST['next'])) {
           Yii::app()->controller->redirect(Yii::app()->controller->createUrl('step2'));
        }
        Yii::app()->controller->render('step1', array('directories' => $this->getDirectories()));
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
