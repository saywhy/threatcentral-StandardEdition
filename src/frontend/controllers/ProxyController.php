<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\FileObj;
use common\models\SensorVersion;
use common\models\Alert;
use common\models\Logger;
use common\models\Config;
use common\models\Email;
use common\models\Command;

use yii\helpers\ArrayHelper;





/**
 * Proxy controller
 */
class ProxyController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // 'logout' => ['post'],
                    // 'test' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public $enableCsrfValidation = false;

    private function isJson(){
        $headers = Yii::$app->request->headers;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    private function getRueryString(){
        parse_str($_SERVER['QUERY_STRING'],$query);
        foreach ($query as $key => &$value) {
            $value = urlencode($value);
        }
        return http_build_query($query);
    }

    private function copyHeader($response){
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->fromArray($response->getHeaders()->toArray());
    }

    public function actionIndex($path)
    {
        $path = $path.'?'.$this->getRueryString();
        $ResultClient = Yii::$app->ResultClient;
        if(Yii::$app->request->isPost)
        {
            $postdata = Yii::$app->request->getRawBody();
            $response = $ResultClient->post($path,$postdata,$_FILES);
        }elseif(Yii::$app->request->isPut) {
            $putdata = Yii::$app->request->getRawBody();
            $response = $ResultClient->put($path,$putdata);
        }elseif(Yii::$app->request->isDelete) {
            $response = $ResultClient->delete($path);
        }else{
            $response = $ResultClient->get($path);
        }
        $this->copyHeader($response);
        return $response->getContent();
    }

    public function actionFeeds($name)
    {
        $name = $name.'?'.$this->getRueryString();
        $ResultClient = Yii::$app->ResultClient;
        $response = $ResultClient->get('/feeds/'.$name);
        $this->copyHeader($response);
        return $response->getContent();
    }

}

























