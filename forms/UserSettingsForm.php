<?php

namespace smart\cms\forms;

use Yii;
use smart\base\Form;

class UserSettingsForm extends Form
{
    /**
     * @var string e-mail
     */
    private $_email;

    /**
     * @var string first name
     */
    public $firstName;

    /**
     * @var string last name
     */
    public $lastName;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('cms', 'E-mail'),
            'firstName' => Yii::t('cms', 'First name'),
            'lastName' => Yii::t('cms', 'Last name'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName'], 'string', 'max' => 50],
        ];
    }

    public function map()
    {
        return [
            [['firstName', 'lastName'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function assignFrom($object, $attributeNames = null)
    {
        parent::assignFrom($object, $attributeNames);
        $this->_email = $object->email;
    }

    /**
     * E-mail getter
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }
}
