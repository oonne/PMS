<?php

namespace frontend\filters;

use Yii;
use yii\filters\auth\AuthMethod;

/**
 * AuthFliter is an action filter that supports the authentication based on the access token passed through a header parameter.
 *
 * @author JAY <JAY@oonne.com>
 */
class AuthFilter extends AuthMethod
{
    /**
     * @var string the parameter name for passing the access token
     */
    public $tokenParam = 'X-Auth-Token';


    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($this->isOptional($action)) {
            return true;
        }

        $request = $this->request ?: Yii::$app->getRequest();
        $response = $this->response ?: Yii::$app->getResponse();

        $identity = $this->authenticate(
            $this->user ?: Yii::$app->getUser(),
            $this->request ?: Yii::$app->getRequest(),
            $response
        );

        if ($identity !== null || $this->isOptional($action)) {
            return true;
        }

        // allow all options method
        $verb = Yii::$app->getRequest()->getMethod();
        if ($verb=='OPTIONS'){
            return true;
        }


        // if not logged in, return 401 empty response
        $response->statusCode = 401;

        // add simple cors headers
        $requestHeaders = $request->getHeaders();
        $responseHeaders = $response->getHeaders();
        $apiOrigin = Yii::$app->params['apiOrigin'];
        if (in_array($requestHeaders['Origin'], $apiOrigin)) {
            $responseHeaders['Access-Control-Allow-Origin'] = $requestHeaders['Origin'];
        }

        // send a empty response
        $response->send();

        return false;
    }

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $accessToken = $request->headers->get($this->tokenParam);
        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
        }

        return null;
    }

}
