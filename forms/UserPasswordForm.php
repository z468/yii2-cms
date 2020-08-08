<?php

namespace smart\cms\forms;

use Yii;
use smart\base\Form;

class UserPasswordForm extends Form
{
    /**
     * @var string old password
     */
    public $oldPassword;

    /**
     * @var string new password
     */
    public $password;

    /**
     * @var string new password confirm
     */
    public $confirm;

    /**
     * @var smart\cms\models\User
     */
    private $_object;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oldPassword' => Yii::t('cms', 'Current password'),
            'password' => Yii::t('cms', 'New password'),
            'confirm' => Yii::t('cms', 'Confirm'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['oldPassword', function($attribute) {
                if (!$this->hasErrors()) {
                    if (!$this->_object->validatePassword($this->$attribute)) {
                        $this->addError($attribute, Yii::t('cms', 'The password is entered incorrectly.'));
                    }
                }
            }],
            ['password', 'string', 'min' => 4],
            ['confirm', 'compare', 'compareAttribute' => 'password'],
            [['oldPassword', 'password', 'confirm'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function assignFrom($object, $attributeNames = null)
    {
        $this->_object = $object;
    }

    /**
     * @inheritdoc
     */
    public function assignTo($object, $attributeNames = null)
    {
        $object->setPassword($this->password);
        $object->passwordChange = false;
    }
}
