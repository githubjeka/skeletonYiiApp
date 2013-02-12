<?php
/**
 * LoginStrategy.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class LoginValidator extends CValidator
{
    public $attributeType = 'username';

    public $minLength = 4;

    public $maxLength = 15;

    public $pattern = null; //default used '/^[-*_a-z0-9.]{4,15}$/i'

    protected function __construct()
    {
        if ($this->pattern === null) {
            $this->pattern = '/^[-*_a-z0-9.]{' . $this->minLength . ',' . $this->maxLength . '}$/i';
        }
    }

    protected function validateAttribute($object, $attribute)
    {

        if ($object->scenario == 'login') {
            $this->validateOnLogin($object, $attribute);
        } elseif ($object->scenario == 'register') {
            switch ($this->attributeType) {
                case 'email':
                    $this->validateEmailOnRegister($object, $attribute);
                    break;
                case 'username':
                    $this->validateUsernameOnRegister($object, $attribute);
                    break;
                default:
                    $validatorList = array();
            }
        } else {
            $object->addError($attribute, 'Scenario not allowed');
        }
    }

    protected function validateUsernameOnRegister($object, $attribute)
    {
        $validator = new CRequiredValidator();
        $validator->attributes = array($attribute);
        $validator->validate($object, $attribute);

        $validator = new CRegularExpressionValidator();
        $validator->pattern = $this->pattern;
        $validator->attributes = array($attribute);
        $validator->validate($object, $attribute);

        $validator = new CUniqueValidator();
        $validator->attributes = array($attribute);
        $validator->validate($object, $attribute);
    }

    protected function validateEmailOnRegister()
    {

    }

    protected function validateOnLogin($object, $attribute)
    {
        $validatorList = array(
            'CRequiredValidator',
            'CExistValidator',
        );
        foreach ($validatorList as $validator) {
            /** @var $valid \CValidator child */
            $valid = new $validator;
            $valid->attributes = array($attribute);
            /** @var $object \CModel */
            $valid->validate($object, $attribute);
        }

        return false;
    }
}