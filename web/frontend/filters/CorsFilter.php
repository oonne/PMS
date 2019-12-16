<?php

namespace frontend\filters;

use Yii;
use yii\filters\Cors;

/**
 * extends Cors
 */
class CorsFilter extends Cors
{
    public function beforeAction($action)
    {
        $this->request = $this->request ?: Yii::$app->getRequest();
        $this->response = $this->response ?: Yii::$app->getResponse();

        $this->overrideDefaultSettings($action);

        $requestCorsHeaders = $this->extractHeaders();
        $responseCorsHeaders = $this->prepareHeaders($requestCorsHeaders);
        $this->addCorsHeaders($this->response, $responseCorsHeaders);

        // clear all options method
        $verb = Yii::$app->getRequest()->getMethod();
        if ($verb=='OPTIONS'){
        	$this->response->send();
        	return false;
        }

        return true;
    }
}
