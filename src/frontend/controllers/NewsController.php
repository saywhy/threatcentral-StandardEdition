<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


use common\models\News;
use common\models\UserLog;







/**
 * News controller
 */
class NewsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['list','update'],
                'rules' => [
                    [
                        'actions' => [''],
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
        $uid = Yii::$app->user->identity->id;
        $newsList = News::find()
                    ->where([
                        'uid' => $uid,
                        'status' => [News::STATUS_UNREAD,News::STATUS_READ]
                    ])
                    ->orderBy([
                        'created_at' => SORT_DESC,
                    ])
                    ->asArray()
                    ->all();
        $data['status'] = 'success';
        $data['data'] = $newsList;
        return $data;
    }

    public function actionUpdate(){
        $this->isAPI();
        $userLog = new UserLog();
        $userLog->type = UserLog::Type_DelUser;
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            $userLog->status = UserLog::Fail;
            $userLog->info = 'Deleting a user failed because it was not a post request';
            $userLog->save();
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
            $userLog->status = UserLog::Fail;
            $userLog->info = 'Deleting the user failed because the request parameter is not valid';
            $userLog->save();
            return $data;
        }
        $news = News::findOne($post['id']);
        if(isset($news)){
            $news->status = $post['status'];
            $news->save();
        }
        $data['status'] = 'success';
        return $data;
    }

}

























