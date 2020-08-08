<?php

namespace smart\cms\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(array_replace([
            'active' => true,
            'mailing' => true,
        ], $config));
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * Auth relation
     * @return yii\db\ActiveQueryInterface
     */
    public function getAuth()
    {
        return $this->hasMany(UserAuth::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * Find user by e-mail
     * @param sring $email 
     * @return User
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Set user password
     * @param string $password 
     * @return void
     */
    public function setPassword($password)
    {
        $this->passwordHash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * Password validation
     * @param string $password 
     * @return boolean
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->passwordHash);
    }

    /**
     * Login
     * @param integer|null $duration 
     * @return boolean
     */
    public function login($duration = null) {
        if ($duration === null) {
            $duration = 3600 * 24 * 30;
        }

        $this->loginDate = gmdate('Y-m-d H:i:s');
        $this->loginIP = Yii::$app->getRequest()->userIP;
        if (empty($this->authKey)) {
            $this->generateAuthKey();
        }

        return $this->save() && Yii::$app->getUser()->login($this, $duration);
    }

    /**
     * Username getter
     * @return string
     */
    public function getUsername()
    {
        $name = trim($this->firstName . ' ' . $this->lastName);
        return empty($name) ? $this->email : $name;
    }

    // /**
    //  * Send confirm e-mail
    //  * @return boolean
    //  */
    // public function sendConfirmEmail()
    // {
    //     if (empty($this->confirmToken)) {
    //         $this->confirmToken = Yii::$app->security->generateRandomString().'_'.time();
    //         if (!$this->save())
    //             return false;
    //     }

    //     $content = Yii::$app->controller->renderFile(dirname(__DIR__) . '/../mail/confirm.php', ['user' => $this]);

    //     $from = $this->email;
    //     if (Yii::$app->mailer->transport instanceof \Swift_SmtpTransport)
    //         $from = Yii::$app->mailer->transport->getUsername();

    //     return Yii::$app->mailer->compose()
    //         ->setFrom($from)
    //         ->setTo($this->email)
    //         ->setSubject(Yii::t('user', 'E-mail confirmation'))
    //         ->setHtmlBody($content)
    //         ->send();
    // }

    /**
     * Find user by e-mail confirm token
     * @param string $token 
     * @return User|null
     */
    public static function findByConfirmToken($token)
    {
        return static::findOne(['confirmToken' => $token]);
    }

    /**
     * Removes e-mail confirm token
     * @return void
     */
    public function removeConfirmToken()
    {
        $this->confirmToken = null;
    }

    /**
     * Check password reset token
     * @param string $token 
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $expire = 3600 * 24;
        $parts = explode('_', $token);
        $timestamp = (integer) end($parts);

        return $timestamp + $expire >= time();
    }

    // /**
    //  * Send reset password e-mail
    //  * @return boolean
    //  */
    // public function sendResetPasswordEmail()
    // {
    //     if (!static::isPasswordResetTokenValid($this->passwordResetToken)) {
    //         $this->passwordResetToken = Yii::$app->security->generateRandomString().'_'.time();
    //         if (!$this->save()) return false;
    //     }

    //     $content = Yii::$app->controller->renderFile(dirname(__DIR__) . '/../mail/reset.php', ['user' => $this]);

    //     $from = $this->email;
    //     if (Yii::$app->mailer->transport instanceof \Swift_SmtpTransport)
    //         $from = Yii::$app->mailer->transport->getUsername();

    //     return Yii::$app->mailer->compose()
    //         ->setFrom($from)
    //         ->setTo($this->email)
    //         ->setSubject(Yii::t('user', 'Password reset'))
    //         ->setHtmlBody($content)
    //         ->send();
    // }

    /**
     * Find user for password reset
     * @param string $token 
     * @return User|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(['passwordResetToken' => $token]);
    }

    /**
     * Removes password reset token
     * @return void
     */
    public function removePasswordResetToken()
    {
        $this->passwordResetToken = null;
    }

    /**
     * Generates auth key for cookie-based login
     * @return void
     */
    protected function generateAuthKey()
    {
        $this->authKey = Yii::$app->getSecurity()->generateRandomString();
    }

    /**
     * @inheritdoc
     * @see yii\web\IdentityInterface
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     * @see yii\web\IdentityInterface
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     * @see yii\web\IdentityInterface
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     * @see yii\web\IdentityInterface
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     * @see yii\web\IdentityInterface
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}
