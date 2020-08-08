<?php

namespace smart\cms\actions;

use Yii;
use yii\base\Action;
use smart\cms\forms\LoginForm;

class LoginAction extends Action
{
    public $view = 'login';

    public function run()
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            return $this->controller->goHome();
        }

        $model = new LoginForm;

        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->controller->goBack();
        };

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
