<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


use common\models\User;
use common\models\UserLog;







/**
 * Userlog controller
 */
class UserlogController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['page'],
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
                        'actions' => [''],
                        'allow' => true,
                        'roles' => ['@'],
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

    public function actionPage($page = 1,$rows = 15){
        $this->isAPI();
        if(Yii::$app->request->isPost)
        {
            $post = json_decode(Yii::$app->request->getRawBody(),true);
            $page = empty($post['page']) ? $page : $post['page'];
            $rows = empty($post['rows']) ? $rows : $post['rows'];
            $StartTime = empty($post['StartTime']) ? null : $post['StartTime'];
            $EndTime = empty($post['EndTime']) ? null : $post['EndTime'];
            $username = empty($post['username']) ? '' : $post['username'];
        }
        $page = (int)$page;
        $rows = (int)$rows;
        $query = UserLog::find()
            ->orderBy([
                'created_at' => SORT_DESC,
            ]);
        $hasWhere = false;
        if($username != ''){
            $query = $query->where(['username' => $username]);
            $hasWhere = true;
        }
        if($EndTime != null){
            $where = ['<','created_at',$EndTime];
            if($hasWhere){
                $query = $query->AndWhere($where);
            }else{
                $query = $query->where($where);
            }
            $hasWhere = true;
        }
        if($StartTime != null){
            $where = ['>','created_at',$StartTime];
            if($hasWhere){
                $query = $query->AndWhere($where);
            }else{
                $query = $query->where($where);
            }
        }

        $page = (int)$page;
        $rows = (int)$rows;
        $count = (int)$query->count();
        $maxPage = ceil($count/$rows);
        $page = $page>$maxPage ? $maxPage : $page;
        $sensorList = $query->offSet(($page-1)*$rows)->limit($rows)->asArray()->all();
        $data = [
            'data' => $sensorList,
            'count' => $count,
            'maxPage' => $maxPage,
            'pageNow' => $page,
            'rows' => $rows,
            'status' => 'success',
        ];
        return $data;
    }
}

























