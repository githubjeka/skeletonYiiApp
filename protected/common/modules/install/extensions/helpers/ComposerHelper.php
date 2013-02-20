<?php
namespace Install\extensions\helpers;

use Composer\Package\Version\VersionParser;

/**
 * ComposerHelper.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class ComposerHelper extends \CController
{
    /**
     * @var string
     */
    protected $cFileJson = 'composer.json';

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
     * @throws \UnexpectedValueException
     * @return string
     */
    protected function getPathComposerFolder()
    {
        if (is_file(\Yii::getPathOfAlias('common') . '/lib/composer/composer.phar')) {
            return \Yii::getPathOfAlias('common') . '/lib/composer/';
        }

        if (is_file(getcwd() . '/common/lib/composer/composer.phar')) {
            return getcwd() . '/common/lib/composer/';
        }
        
        throw new \UnexpectedValueException('File composer.phar not found');
    }


    /**
     * Get array installed module
     * List is taken from the file of default: (backend/module/) . composer/installed.json
     * @return array or false
     */
    protected function getInstalledModules()
    {
        if (file_exists($path = \Yii::app()->getModulePath() . '/composer/installed.json')) {
            $installed = file_get_contents($path);

            return \CJSON::decode($installed);
        }

        return false;
    }

    /**
     * @return array
     */
    protected function getStoredModules()
    {
        if ($cacheModules = glob($this->getPathComposerFolder() . '/cache/*', GLOB_ONLYDIR)) {
            $list = array();
            foreach ($cacheModules as $pathModule) {
                $name = basename($pathModule);
                foreach (glob($pathModule . '/*') as $version) {
                    $list[$name][] = basename($version, '-.zip');
                }
            }
            $cacheModules = $list;
        }
        return $cacheModules;
    }

    /**
     * Json encode $newArrayconfig and put in composer.json
     * @param $newArrayConfig
     */
    protected function putFileComposer($newArrayConfig)
    {
        if (is_array($newArrayConfig)) {
            $newJson = \CJSON::encode($newArrayConfig);
            file_put_contents($this->getPathComposerFolder() . $this->cFileJson, $newJson);
        }
    }

    /**
     * Get json data from composer.json
     */
    protected function getFileComposer($returnArray = true)
    {
        if (file_exists($this->getPathComposerFolder() . $this->cFileJson)) {
            return \CJSON::decode(file_get_contents($this->getPathComposerFolder() . $this->cFileJson), $returnArray);
        }
        return false;
    }

    /**
     * Create default json data for composer.json
     * NOTE: Not valid for install, because require is empty
     */
    protected function createDefaultFileComposer()
    {
        if (!$this->getFileComposer()) {
            $composer = array(
                'config' => array(
                    'vendor-dir' => \Yii::app()->getModulePath(),
                    'cache-files-dir' => $this->getPathComposerFolder() . 'cache'
                ),
                'repositories' => array(array('packagist' => false)),
                'require' => array()
            );
            $this->putFileComposer($composer);
        }
    }

    /**
     * Delete duplicates element from composer.json
     * Return new composer.json
     */
    protected function deleteDuplicatesFromJsonFile()
    {
        if ($json = $this->getFileComposer()) {
            $result = array_reduce(
                $json['repositories'],
                function ($a, $b) {
                    static $stored = array();

                    $hash = md5(serialize($b));

                    if (!in_array($hash, $stored)) {
                        $stored[] = $hash;
                        $a[] = $b;
                    }

                    return $a;
                },
                array()
            );

            $json['repositories'] = $result;
            $this->putFileComposer($json);
        }
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
     * @throws \ErrorException if file composer.json not found after create
     * @return void
     */
    protected function addComposerConfig($additive = array())
    {
        $this->createDefaultFileComposer();
        $composer = $this->getFileComposer();
        if ($composer) {
            if (array_key_exists('require', $composer)) {
                $composer['require'] = \CMap::mergeArray($composer['require'], $additive['require']);
            } else {
                $composer['require'] = $additive['require'];
            }
            $composer['repositories'] = \CMap::mergeArray($composer['repositories'], $additive['repositories']);
        } else {
            throw new \ErrorException($this->cFileJson . ' not found');
        }

        $this->putFileComposer($composer);
        $this->deleteDuplicatesFromJsonFile();
    }

    /**
     * Return new Package Module
     * Note: Action only return array, not add to composer.json
     * @param $name
     * @param $version
     * @param $url
     * @return array
     */
    protected function createPackage($name, $version, $url)
    {
        return array(
            'require' => array(
                $name => $version
            ),
            'repositories' => array(
                array(
                    'type' => 'package',
                    'package' => array(
                        'name' => $name,
                        'version' => $version,
                        'dist' => array(
                            'url' => $url,
                            'type' => 'zip',
                        ),
                    ),
                )
            )
        );
    }

    /**
     * see more info https://getcomposer.org/doc/04-schema.md#version
     * @param null $version
     * @return mixed|string
     */
    protected function normalizeVersion($version)
    {
        require_once("phar://" . $this->getPathComposerFolder() .
            '/composer.phar/src/Composer/Package/Version/VersionParser.php');

        $versionClass = new VersionParser;
        try {
            return $versionClass->normalize($version);
        } catch (\UnexpectedValueException $e) {
            return '1.0.0.0';
        }
    }
}
