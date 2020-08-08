<?php

namespace smart\cms\actions;

use Yii;
use yii\base\Action;
use smart\cms\forms\UserPasswordForm;

class UserPasswordAction extends Action
{
    public $view = 'password';

    public function run()
    {
        $user = Yii::$app->getUser();

        // Check login
        if ($user->getIsGuest()) {
            return $user->loginRequired();
        }

        $object = $user->getIdentity();
        $model = new UserPasswordForm;
        $model->assignFrom($object);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->assignTo($object);
            if ($object->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('cms', 'The new password has been set.'));
            }
            return $this->controller->goHome();
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
