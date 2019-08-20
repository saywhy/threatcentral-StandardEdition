<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\ArrayHelper;

use common\models\Group;
use common\models\GroupUser;
use common\models\Config;






/**
 * Group controller
 */
class GroupController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        if(Config::getLicense()['validLicenseCount'] == 0){
            $rules = [];
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
                    'actions' => ['list'],
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
                'only' => ['list','update','remove','users','remove-user'],
                'rules' => $rules,
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

    public function actionList(){
        $this->isAPI();
        $GroupList = Group::find()->orderBy('level')->all();
        $GroupList = ArrayHelper::toArray($GroupList);
        $data = [
            'data' => $GroupList,
            'status' => 'success',
        ];
        return $data;
    }
    
    public function actionUpdate(){
        $this->isAPI();
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            return $data;
        }
        if(Yii::$app->request->isPost)
        {
            $post = json_decode(Yii::$app->request->getRawBody(),true);
        }
        if(empty($post))
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Rawbody not JSON';
            return $data;
        }
        if(isset($post['id'])){
            $group = Group::findOne($post['id']);
        }
        if(empty($group)){
            $group = new Group();
        }
        $group->text = $post['text'];
        $group->type = $post['type'];
        $group->pid = $post['pid'];
        $group->FilterList = $post['FilterList'];
        $group->setLevel();
        $group->save();
        $data['status'] = 'success';
        $data['group'] = ArrayHelper::toArray($group);
        return $data;
    }

    public function actionRemove(){
        $this->isAPI();
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            return $data;
        }
        if(Yii::$app->request->isPost)
        {
            $post = json_decode(Yii::$app->request->getRawBody(),true);
        }
        if(empty($post))
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Rawbody not JSON';
            return $data;
        }
        if(isset($post['idList'])){
            Group::deleteAll(['id'=>$post['idList']]);
        }
        $data['status'] = 'success';
        return $data;
    }

    public function actionUsers(){
        $this->isAPI();
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            return $data;
        }
        if(Yii::$app->request->isPost)
        {
            $post = json_decode(Yii::$app->request->getRawBody(),true);
        }
        if(empty($post))
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Rawbody not JSON';
            return $data;
        }
        $group = Group::findOne($post['id']);
        $userList = $group->getUserList();
        $data['status'] = 'success';
        $data['list'] = ArrayHelper::toArray($userList);
        return $data;
    }
    public function actionRemoveUser(){
        $this->isAPI();
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            return $data;
        }
        if(Yii::$app->request->isPost)
        {
            $post = json_decode(Yii::$app->request->getRawBody(),true);
        }
        if(empty($post))
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Rawbody not JSON';
            return $data;
        }
        GroupUser::deleteAll(['uid' => $post['uid'],'gid' => $post['gid']]);
        $group = Group::findOne($post['gid']);
        $userList = $group->getUserList();
        $data['status'] = 'success';
        $data['list'] = ArrayHelper::toArray($userList);
        return $data;
    }
}




















