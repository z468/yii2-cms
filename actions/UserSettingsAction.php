<?php

namespace smart\cms\actions;

use Yii;
use yii\base\Action;
use smart\cms\forms\UserSettingsForm;

class UserSettingsAction extends Action
{
    public function run()
    {
        $user = Yii::$app->getUser();

        if ($user->getIsGuest()) {
            return $this->controller->goHome();
        }

        $object = $user->getIdentity();

        $model = new UserSettingsForm;
        $model->assignFrom($object);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->assignTo($object);
            $object->save(false);

            Yii::$app->getSession()->setFlash('success', Yii::t('cms', 'Changes saved successfully.'));
            return $this->controller->refresh();
        }

        return $this->controller->render('settings', [
            'model' => $model,
        ]);
    }
}
