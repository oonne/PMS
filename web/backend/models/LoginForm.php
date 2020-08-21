<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\UserAccount;
use common\models\Config;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_account;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $account = $this->getAccount();
            if (!$account || !$account->validatePassword($this->password)) {
                $this->addError($attribute, '帐号密码错误');
            }
            if ($account->Type == UserAccount::ACCOUNT_HERITAGE) {
                // 遗产继承人登录
                $config = Config::find()
                        ->where(['sConfigKey' => 'LAST_ACCESS'])
                        ->one();
                if ($config->tConfigValue != 'DANGER' && $config->tConfigValue != 'DEAD') {
                    $this->addError($attribute, '加一最后登录时间：'.$config->tConfigValue);
                }
            } else if ($account->Type != UserAccount::ACCOUNT_WEB) {
                // 只有web账户可以登录
                $this->addError($attribute, '无登录权限');
            }
        } else {
            return $this->goHome();
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '帐号',
            'password' => '密码',
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $account = $this->getAccount();
            $account->save(false);
            return Yii::$app->user->login($account, 3600 * 24);
        } else {
            return false;
        }
    }

    /**
     * Finds Account by [[username]]
     *
     * @return Account|null
     */
    public function getAccount()
    {
        if ($this->_account === null) {
            $this->_account =  UserAccount::findByUserName($this->username);
        }

        return $this->_account;
    }
}
