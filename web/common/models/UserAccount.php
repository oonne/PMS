<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $uUserID
 * @property string $sUserName
 * @property string $sNickName
 * @property string $sPasswordHash
 * @property string $sAccessToken
 * @property string $sAuthKey
 * @property string $RoleType
 */
class UserAccount extends ActiveRecord implements IdentityInterface
{
    public $password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sUserName'], 'required'],
            [['sUserName'], 'match', 'pattern' => '/^[A-Za-z_-][A-Za-z0-9_-]+$/'],
            [['sUserName'], 'string', 'max' => 64],
            [['sUserName'], 'unique'],

            [['password'], 'required', 'on' => ['creation']],
            [['password'], 'trim'],
            [['password'], 'match', 'pattern' => '/^\S+$/'],
            [['password'], 'string', 'length' => [6, 32]],
            [['password'], 'default'],

            [['sAccessToken'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uUserID' => 'U User ID',
            'sUserName' => '设备名',
            'password' => '密码',
            'sPasswordHash' => '密码hash',
            'sAccessToken' => 'Token',
            'sAuthKey' => '记住密码key',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['uUserID' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['sAccessToken' => $token]);
    }

    /**
     * Finds user by user name
     *
     * @param string $name
     * @return static|null
     */
    public static function findByUserName($name)
    {
        return static::findOne(['sUserName' => $name]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->sAuthKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->sPasswordHash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->sPasswordHash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->sAuthKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates access token
     */
    public function generateAccessToken()
    {
        $this->sAccessToken = Yii::$app->security->generateRandomString();
    }

    /**
     * Removes access token
     */
    public function removeAccessToken()
    {
        $this->sAccessToken = null;
    }

    public function __toString()
    {
        return $this->sUserName;
    }
}
