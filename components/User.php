<?php

namespace smart\cms\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use smart\cms\Module;

class User extends \yii\web\User
{
    /**
     * @inheritdoc
     */
    public $loginUrl = ['/cms/user/login'];

    /**
     * @var sring|array the URL for password change when identity has property [[changePassword]] and it set to true.
     */
    public $passwordChangeUrl = ['/cms/user/password'];

    /**
     * @var boolean
     */
    private $_isAdmin;

    /**
     * @var string
     */
    private $_username;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Module::translation();
    }

    /**
     * Checking that user is administrator
     * @return boolean
     */
    public function getIsAdmin()
    {
        if ($this->_isAdmin !== null) {
            return $this->_isAdmin;
        }

        if ($this->getIsGuest()) {
            return false;
        }

        return $this->_isAdmin = (boolean) ArrayHelper::getValue($this->getIdentity(), 'admin');
    }

    /**
     * @inheritdoc
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if ($this->getIsAdmin()) {
            return true;
        }

        return parent::can($permissionName, $params, $allowCaching);
    }

    /**
     * Return user name
     * @return string
     */
    public function getUsername()
    {
        if ($this->_username !== null) {
            return $this->_username;
        }

        if ($this->getIsGuest()) {
            return Yii::t('cms', 'Guest');
        }

        return $this->_username = ArrayHelper::getValue($this->getIdentity(), 'username');
    }

    /**
     * Redirects the user browser to the password change page.
     * 
     * @param bool $checkAjax whether to check if the request is an AJAX request. When this is true and the request
     * is an AJAX request, the current URL (for AJAX request) will NOT be set as the return URL.
     * @param bool $checkAcceptHeader whether to check if the request accepts HTML responses. Defaults to `true`. When this is true and
     * the request does not accept HTML responses the current URL will not be SET as the return URL. Also instead of
     * redirecting the user an ForbiddenHttpException is thrown.
     * @return Response the redirection response if [[passwordChangeUrl]] is set
     * @throws ForbiddenHttpException the "Access Denied" HTTP exception if [[passwordChangeUrl]] is not set or a redirect is
     * not applicable.
     */
    public function passwordChangeRequired($checkAjax = true, $checkAcceptHeader = true)
    {
        $passwordChangeUrl = (array) $this->passwordChangeUrl;
        if ($passwordChangeUrl[0] === Yii::$app->requestedRoute) {
            return;
        }

        $request = Yii::$app->getRequest();
        $canRedirect = !$checkAcceptHeader || $this->checkRedirectAcceptable();
        if ($this->enableSession
            && $request->getIsGet()
            && (!$checkAjax || !$request->getIsAjax())
            && $canRedirect
        ) {
            $this->setReturnUrl($request->getUrl());
        }
        if ($this->passwordChangeUrl !== null && $canRedirect) {
            $passwordChangeUrl = (array) $this->passwordChangeUrl;
            if ($passwordChangeUrl[0] !== Yii::$app->requestedRoute) {
                return Yii::$app->getResponse()->redirect($this->passwordChangeUrl);
            }
        }
        throw new ForbiddenHttpException(Yii::t('user', 'Password change required.'));
    }
}
