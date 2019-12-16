<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\helpers\wxBizDataCrypt;
use frontend\helpers\exportMsg;
use common\models\WxSkey;

class WxController extends Controller
{

    protected function verbs()
    {
        return [
            'get-wx-session' => ['get'],
            'decrypt-data' => ['post'],
        ];
    }

    /**
     * Get Wechat sessionKey
     */
    public function actionGetWxSession($code)
    {
        $wxAppID = Yii::getAlias(Yii::$app->params['wxAppID']);
        $wxSessionKey = Yii::getAlias(Yii::$app->params['wxSessionKey']);

        // 通过微信接口，拿code换取sessionKey
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$wxAppID.'&secret='.$wxSessionKey.'&js_code='.$code.'&grant_type=authorization_code';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $sessionData = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($sessionData);
        if(array_key_exists('errcode', $data)){
            return exportMsg::error('103001');
        }

        // 把sessionKey写入数据库
        $model = WxSkey::findOne(['sOpenID' => $data->openid]);
        if (!$model) {
            $model = new WxSkey();
            $model->setScenario('creation');
        }
        $sessionInfo = [
            'sOpenID' => $data->openid,
            'sSessionKey' => $data->session_key
        ];

        if ($model->load($sessionInfo, '') && $model->validate()) {
            $model->generateWxToken();
            $model->save(false);
        }
        return [
            'Ret' => 0,
            'Data' => [
                'wxToken' => $model->sWxToken
            ]
        ];  
    }


    /**
     * Decrypt Wechat data
     */
    public function actionDecryptData()
    {
        $wxAppID = Yii::getAlias(Yii::$app->params['wxAppID']);
        $params = Yii::$app->request->post();
        $encryptedData = $params['encryptedData'];
        $iv = $params['iv'];
        $wxToken = $params['wxToken'];

        $sessionData = WxSkey::findOne(['sWxToken' => $wxToken]);
        if (!$sessionData) {
            return exportMsg::error('103015');  
        }
        $sessionKey = $sessionData->sSessionKey;

        $pc = new WXBizDataCrypt($wxAppID, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );

        if ($errCode != 0) {
            return exportMsg::error($errCode); 
        }else{
            return [
                'Ret' => 0,
                'Data' => json_decode($data)
            ];    
        }
    }

}