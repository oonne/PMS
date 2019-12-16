<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\UserAccount;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;

    /**
     * @var User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],

            [['username'], 'match', 'pattern' => '/^[A-Za-z_-][A-Za-z0-9_-]+$/'],
            [['username'], 'string', 'max' => 32],

            [['password'], 'match', 'pattern' => '/^\S+$/'],
            [['password'], 'string', 'length' => [6, 32]],
            [['password'], 'validatePassword']
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
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, '帐号或密码不正确！');
            } else {
                if (!$user->validatePassword($this->password)) {
                    $this->addError($attribute, '帐号或密码不正确！');
                }
            }
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
            $user = $this->getUser();
            $user->generateAccessToken();
            return $user->save(false) && Yii::$app->user->loginByAccessToken($user->sAccessToken);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = UserAccount::findByUserName($this->username);
        }

        return $this->_user;
    }
}
