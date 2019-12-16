<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\filters\AuthFilter;
use common\models\Users;
use frontend\models\LoginForm;

class UserController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => AuthFilter::className(),
            'only' => ['get-user-info']
        ];
        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'login' => ['post'],
            'get-user-info' => ['get'],
        ];
    }

    /**
     * Login
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->post(), '');

        if ($model->login()) {
            $data = $model->user->toArray(['sUserName', 'sAccessToken']);
            return [
                'Ret' => 0,
                'Data' => $data
            ];
        } else {
            Yii::warning('用户登录失败！');
            return [
                'Ret' => '102000',
                'Data' => [
                    'errors' => $model->getFirstErrors()
                ]
            ];
        }
    }

    /**
     * Get user info
     */
    public function actionGetUserInfo()
    {
        $identity = Yii::$app->user->identity;
        $data = $identity->toArray(['sUserName', 'sAccessToken']);
        
        return [
            'Ret' => 0,
            'Data' => $data
        ];
    }

}