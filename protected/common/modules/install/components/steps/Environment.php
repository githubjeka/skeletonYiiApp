<?php
use \Install\extensions\helpers\AbstractStepBehavior;

/**
 * Environment.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

class Environment extends AbstractStepBehavior
{
    public function onStep()
    {
        $this->_header=Yii::t('install','Environment');
        $this->renderView(array('valid'=>$this->validate()));
    }

    public function validate()
    {
        return $this->cheekRightFolder();
    }

    private function cheekRightFolder()
    {
        foreach ($this->getDirectories() as $path) {
            if (!$this->isWritable($path)) {
                $errors[] = $path . Yii::t('install',' must exist and be writable');
            }
        }
        if (isset($errors)) {
            $this->_errors=$errors;
            return false;
        }

        return true;
    }

    private function getDirectories()
    {
        $root = realpath(\Yii::getPathOfAlias('root'));

        $directories = \SplFixedArray::fromArray(
            array(
                $root . '/frontend/www/assets',
                $root . '/backend/www/assets',
                $root . '/frontend/runtime',
                $root . '/backend/runtime',
                $root . '/console/runtime',
                $root . '/common/config/params-prod.php'
            )
        );

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
    private function isWritable($path)
    {
        return is_writable($path) || @chmod($path, 0777) && is_writable($path);
    }
}
