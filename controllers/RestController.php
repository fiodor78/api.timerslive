<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use app\models\LoginForm;
use backend\behaviours\CorsCustom;


class RestController extends Controller
{

    public $request;

    public $enableCsrfValidation = false;

    public $headers;

    public function behaviors()

    {

        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [

            'class' => ContentNegotiator::className(),

            'formats' => [

                'application/json' => Response::FORMAT_JSON,

            ],

        ];

        // remove authentication filter

        $auth = $behaviors['authenticator'];

        unset($behaviors['authenticator']);

        // add CORS filter

        $behaviors['corsFilter'] = [

        'class' => CorsCustom::className(),

            ];

        // re-add authentication filter

        $behaviors['authenticator'] = $auth;

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)

        $behaviors['authenticator']['except'] = ['options'];

        $behaviors['authenticator'] = [

        'class' => HttpBearerAuth::className(),

        'except'=>['login']

            ];


        return $behaviors;

    }

    /*

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Allow-Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => []
            ]

        ];
        
        return $behaviors;
    }

    */

    public function init()
    {
        $this->request = json_decode(file_get_contents('php://input'), true);

        if ($this->request && !is_array($this->request)) {
            Yii::$app->api->sendFailedResponse(['Invalid Json']);
        }
    }
}
