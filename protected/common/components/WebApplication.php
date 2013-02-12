<?php
/**
 * WebApplication.php
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class WebApplication extends CWebApplication
{
    /**
     * We were getting tons of errors in the logs from OPTIONS requests for the URI "*"
     * As it turns out, the requests were from localhost (::1) and are apparently a way
     * that Apache polls its processes to see if they're alive. This function causes
     * Yii to respond without logging errors.
     */
    public function runController($route)
    {
        try {
            parent::runController($route);
        } catch (CHttpException $e) {
            throw $e;
        }
    }

    protected function init()
    {
        if (strlen($this->getBaseUrl()) > 0) {
            $this->assetManager->setBaseUrl(
                $this->getBaseUrl() . $this->assetManager->getBaseUrl()
            );
            $this->themeManager->setBaseUrl(
                $this->getBaseUrl() . $this->themeManager->getBaseUrl()
            );
        }

        if ($modules = glob($this->getBasePath() . '/modules/onNamespace/*', GLOB_ONLYDIR)) {
            foreach ($modules as $moduleDirectory) {
                $this->setModules(
                    array(
                        basename($moduleDirectory) => array(
                            'class' => '\backend\modules\onNamespace\\' . strtolower(
                                basename($moduleDirectory)
                            ) . '\\' . ucfirst(
                                basename($moduleDirectory)
                            ) . 'Module'
                        )
                    )
                );
            }
        }
        if ($modules = glob($this->getBasePath() . '/modules/offNamespace/*', GLOB_ONLYDIR)) {
            foreach ($modules as $moduleDirectory) {
                $this->setModules(array(basename($moduleDirectory)));
            }
        }

        parent::init();
    }
}