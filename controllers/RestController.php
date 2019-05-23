<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use app\models\LoginForm;



class RestController extends Controller
{

    public $request;

    public $enableCsrfValidation = false;

    public $headers;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            // For cross-domain AJAX request
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to domains:
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600, // Cache (seconds)
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page', 'X-Pagination-Page-Count', 'X-Pagination-Per-Page', 'X-Pagination-Total-Count'],
                ],
            ],
        ]);
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

        if($this->request&&!is_array($this->request)){
            Yii::$app->api->sendFailedResponse(['Invalid Json']);

        }

    }

}


