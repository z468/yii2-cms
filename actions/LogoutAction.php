<?php

namespace smart\cms\actions;

use Yii;
use yii\base\Action;

class LogoutAction extends Action
{
    public function run()
    {
        if (Yii::$app->getUser()->getIsGuest()) {
            return $this->controller->goHome();
        }

        Yii::$app->getUser()->logout();

        return $this->controller->goBack();
    }
}
