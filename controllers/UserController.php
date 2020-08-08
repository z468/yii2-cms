<?php

namespace smart\cms\controllers;

use Yii;
use yii\web\Controller;

class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'login' => 'smart\cms\actions\LoginAction',
            'logout' => 'smart\cms\actions\LogoutAction',
            'password' => 'smart\cms\actions\UserPasswordAction',
            'settings' => 'smart\cms\actions\UserSettingsAction',
        ];
    }

    /**
     * @inheritdoc
     * Set custom layout for login page
     */
    public function beforeAction($action)
    {
        if ($action->id === 'login') {
            $this->layout = 'login';
        }
        return parent::beforeAction($action);
    }
}
