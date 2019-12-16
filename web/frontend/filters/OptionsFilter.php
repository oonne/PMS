<?php

namespace frontend\filters;

use Yii;
use yii\filters\VerbFilter;
use yii\base\ActionEvent;
use yii\web\MethodNotAllowedHttpException;

/**
 * extends VerbFilter
 * allow all options method
 * 
 */

class OptionsFilter extends VerbFilter {


    /**
     * @param ActionEvent $event
     * @return bool
     * @throws MethodNotAllowedHttpException when the request method is not allowed.
     */
    public function beforeAction($event)
    {
        $action = $event->action->id;
        if (isset($this->actions[$action])) {
            $verbs = $this->actions[$action];
        } elseif (isset($this->actions['*'])) {
            $verbs = $this->actions['*'];
        } else {
            return $event->isValid;
        }

        $verb = Yii::$app->getRequest()->getMethod();
        if ($verb=='OPTIONS'){
        	return $event->isValid;
        }
        $allowed = array_map('strtoupper', $verbs);
        if (!in_array($verb, $allowed)) {
            $event->isValid = false;
            // https://tools.ietf.org/html/rfc2616#section-14.7
            Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $allowed));
            throw new MethodNotAllowedHttpException('Method Not Allowed. This URL can only handle the following request methods: ' . implode(', ', $allowed) . '.');
        }

        return $event->isValid;
    }
}

