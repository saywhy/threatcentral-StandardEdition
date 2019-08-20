<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\SensorVersion;
use common\models\ProFile;
use common\models\Group;
use common\models\GroupUser;
use common\models\Config;
use yii\helpers\ArrayHelper;



/**
 * Seting controller
 */
class SetingController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        if(Config::getLicense()['validLicenseCount'] == 0){
            $rules = [
                [
                    'actions' => ['user','license','network','log','infoImport'],
                    'allow' => true,
                    'roles' => ['admin'],
                ],
            ];
        }else{
            $rules = [
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
                    'actions' => [''],
                    'allow' => true,
                    'roles' => ['user'],
                ],
                [
                    'actions' => [],
                    'allow' => true,
                    'roles' => ['admin'],
                ],
            ];
        }
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['license','network','group','user','log','addgroups','prototype','infoImport'],
                'rules' => $rules,
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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

    public function actionIndex()
    {
        if(Config::getLicense()['validLicenseCount'] == 0){
            return $this->redirect('/seting/license'); 
        }else{
            return $this->render('index');
        }
    }

    public function actionNetwork()
    {
        return $this->render('network');
    }

    public function actionLicense()
    {
        return $this->render('license');
    }

    public function actionPrototype()
    {
        return $this->render('prototype');
    }

    public function actionGroup()
    {
        $GroupList = Group::find()->orderBy('level')->all();
        $GroupList = ArrayHelper::toArray($GroupList);
        return $this->render('group',['GroupList'=>$GroupList]);
    }

    public function actionUser()
    {
        if(Config::getLicense()['validLicenseCount'] == 0){
            return $this->redirect('/seting/license');
        }else{
            $GroupList = Group::find()->orderBy('level')->all();
            $GroupList = ArrayHelper::toArray($GroupList);
            return $this->render('user',['GroupList'=>$GroupList]);
        }
    }

    public function actionLog()
    {
        return $this->render('log');
    }

    public function actionAddgroups()
    {   
        $this->isAPI();
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            return $data;
        }
        $post = json_decode(Yii::$app->request->getRawBody(),true);
        $data['success'] = 0;
        $data['fail'] = 0;
        foreach ($post['uidList'] as $key => $uid) {
            $groupUser = GroupUser::find()->where(['uid' => $uid, 'gid' => $post['gid']])->one();
            if(isset($groupUser)){
                $data['fail']++;
                continue;
            }
            $groupUser = new GroupUser();
            $groupUser->uid = $uid;
            $groupUser->gid = $post['gid'];
            $groupUser->save();
            $data['success']++;
        }
        $data['status'] = 'success';
        return $data;
    }
}

























