<?php
namespace Install\extensions\helpers;

/**
 * AbstractStepBehavior.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
abstract class AbstractStepBehavior extends \CBehavior
{
    protected $_prevBtn = true;
    protected $_nextBtn = true;
    protected $_header = null;
    protected $_errors = array();

    private function getStepName()
    {
        return strtolower(get_called_class());
    }

    public function getHasError()
    {
        return $this->_hasError;
    }

    protected function renderView($params = array())
    {
        // header
        \Yii::app()->controller->render(
            '_head',
            array(
                'header' => (isset($this->_header)) ? $this->_header : $this->getStepName()
            )
        );

        // errors
        if (!empty($this->_errors)) {
            \Yii::app()->controller->render(
                '_errors',
                array(
                    'errors' => $this->_errors,
                )
            );
        }

        // content
        \Yii::app()->controller->render($this->getStepName(), $params);

        // buttons
        \Yii::app()->controller->render(
            '_nav',
            array(
                'prev' => $this->_prevBtn,
                'next' => $this->_nextBtn,
            )
        );
    }

    abstract public function onStep();
    abstract public function validate();
}