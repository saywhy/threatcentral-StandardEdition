<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\GroupNode;
use common\models\Config;


/**
 * Agent controller
 */
class AgentController extends Controller
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
                    'actions' => ['index','group-list'],
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
                'only' => ['index','group-list','group-save','group-del'],
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
        return $this->render('index');
    }

    public function actionGroupList()
    {
        $this->isAPI();
        $data['status'] = 'success';
        $data['data'] = ArrayHelper::toArray(GroupNode::find()->all());
        return $data;
    }

    public function actionGroupDel($id)
    {
        $this->isAPI();
        if(GroupNode::deleteAll(['id' => $id])){
            $data['status'] = 'success';
        }else{
            $data['status'] = 'fail';
        }
        return $data;
    }

    public function actionGroupSave()
    {
        $this->isAPI();
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            return $data;
        }
        if(Yii::$app->request->isPost)
        {
            $RawBody = trim(Yii::$app->request->getRawBody());
            $post = json_decode($RawBody,true);
        }
        if(empty($post))
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Rawbody not JSON.';
            return $data;
        }
        if(array_key_exists('id', $post)){
            $group = GroupNode::findOne($post['id']);
        }
        if(empty($group))
        {
            $group = new GroupNode();
        }
        $group->name = $post['name'];
        $group->nodes = array_key_exists('nodes', $post) ? $post['nodes'] : [];
        $group->save();
        $data['id'] = $group->id;
        $data['status'] = 'success';
        return $data; 
    }
}

























