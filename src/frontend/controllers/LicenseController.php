<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\License;
use common\models\Config;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;



/**
 * License controller
 */
class LicenseController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['import','get'],
                'rules' => [
                    [
                        'actions' => [''],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [''],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['get'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
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
        //         var_dump(Yii::$app->user);
        // exit;
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

    private function isAPI(){
        $headers = Yii::$app->request->headers;
        if(stristr($headers['accept'],'application/json') !== false)
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }else
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        }
    }

    public function actionImport()
    {
        $this->isAPI();
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            return $data;
        }
        $post = json_decode(Yii::$app->request->getRawBody(),true);
        if(empty($post['bin'])){
            $data['status'] = 'fail';
            $data['errorMessage'] = 'bin error';
            return $data;
        }


        $bin = $post['bin'];
        $license = new License($bin);
        $details = $license->details;
        if(empty($details) || (array_key_exists('product', $details) && $details['product'] != 'ThreatCentral')){
            $data['status'] = 'fail';
            $data['errorMessage'] = 'license error';
        }
        else if(array_key_exists('endTime', $details) && ($details['endTime'] > time()*1000)){
            $data['status'] = 'success';
            $data['SN'] = $details['SN'];
            $data['license'] = $license->import();
        }else{
            $data['status'] = 'fail';
            $data['errorMessage'] = 'timeout error';
        }
        return $data;
    }

    public function actionGet()
    {
        $this->isAPI();
        $data['status'] = 'success';
        $data['license'] = Config::getLicense();
        $data['key'] = Yii::$app->cache->get('MachineCode');
        return $data;
    }

    public function actionOnline()
    {
        $this->isAPI();
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            return $data;
        }
        $post = json_decode(Yii::$app->request->getRawBody(),true);
        $httpclient = new Client([
            'baseUrl' => 'https://license.hoohoolab.com',
            'transport' => 'yii\httpclient\CurlTransport',
        ]);

        $url = '/api/license?SN='.$post['SN'].'&key='.$post['key'];
        $request = $httpclient->createRequest()->setMethod('get')->setUrl($url);
        $request->addOptions(['sslVerifyPeer' => false]);
        $request->addHeaders(['accept' => 'application/json']);
        $response = $request->send();
        $content = json_decode($response->content,true);
        return $content;
    }
}

























