<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\models\ResetPasswordForm;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


use common\models\User;
use common\models\UserLog;
use common\models\Config;







/**
 * User controller
 */
class UserController extends Controller
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
                    'actions' => ['get-self-password-reset-token','reset-self-password'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'actions' => ['page'],
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
                'only' => ['page','add','del','get-self-password-reset-token','get-password-reset-token','reset-self-password','reset-password'],
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

    public function actionPage($page = 1,$rows = 15){
        $this->isAPI();
        if(Yii::$app->request->isPost)
        {
            $post = json_decode(Yii::$app->request->getRawBody(),true);
            $page = empty($post['page']) ? $page : $post['page'];
            $rows = empty($post['rows']) ? $rows : $post['rows'];
        }
        $page = (int)$page;
        $rows = (int)$rows;
        $query = User::find()
            ->orderBy([
                'id' => SORT_DESC,
            ]);
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
    

    public function actionAdd(){
        $this->isAPI();
        $userLog = new UserLog();
        $userLog->type = UserLog::Type_AddUser;
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            $userLog->status = UserLog::Fail;
            $userLog->info = 'The add user failed because it was not a post request';
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
            $userLog->info = 'The add user failed because the request parameter is not valid';
            $userLog->save();
            return $data;
        }

        $user = User::find()->where(['username'=>$post['username']])->one();

        if(empty($user)){
            $user = new User();
            $user->username = $post['username'];
            $user->setPassword($post['password']);
            $user->email = uniqid();
            $user->role = $post['role'];
            $user->creator = Yii::$app->user->identity->id;
            $user->creatorname = Yii::$app->user->identity->username;
            $user->generateAuthKey();
            $user->save();
            $userLog->status = UserLog::Success;
            $userLog->info = 'User \''.$user->username.'\' was added';
            $userLog->save();
            return $this->actionPage($post['page']);
        }else{
            $data['status'] = 'fail';
            $data['errorMessage'] = 'This user already exists';
            $data['errorCode'] = 1;
            return $data;
        }
    }

    public function actionDel(){
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

        $user = User::findOne($post['id']);
        if(isset($user)){
            $user->delete();
            $userLog->status = UserLog::Success;
            $userLog->info = 'User \''.$user->username.'\' was deleted';
            $userLog->save();
        }
        return $this->actionPage($post['page']);
    }

    public function actionGetSelfPasswordResetToken(){
        return $this->actionGetPasswordResetToken(Yii::$app->user->identity->id);
    }

    public function actionGetPasswordResetToken($id){
        $this->isAPI();
        $user = User::findOne($id);
        if(empty($user)){
            return [
                'errorMessage' => 'This user does not exist.',
                'status' => 'fail',
            ];
        }
        $token = $user->generatePasswordResetToken();
        $user->save();
        $data = [
            'token' => $user->password_reset_token,
            'status' => 'success',
        ];
        return $data;
    }

    public function actionResetSelfPassword($token)
    {
        $this->isAPI();
        $old_password = Yii::$app->request->post()['old_password'];
        if(!Yii::$app->user->identity->validatePassword($old_password)){
            return [
                'status' => 'fail',
                'errorMessage' => 'Password error'
            ];
        }else{
            return $this->resetPassword($token);
        }
    }

    public function actionResetPassword($token)
    {
        $this->isAPI();
        $user = User::findByPasswordResetToken($token);
        if($user == null){
            $username = 'unknown';
        }else{
            $username = $user->username;
        }
        $data = $this->resetPassword($token);
        $userLog = New UserLog();
        $userLog->type = UserLog::Type_resetPassword;
        if($data['status'] == 'success'){
            $userLog->status = UserLog::Success;
            $userLog->info = 'User '.$username.'\'s password reset success';
        }else{
            $userLog->status = UserLog::Fail;
            $userLog->info = 'User '.$username.'\'s password reset failure';
        }
        $userLog->save();
        return $data;
    }

    private function resetPassword($token){
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');
            return [
                'status' => 'success',
            ];
        }
        return [
            'status' => 'fail',
        ];
    }
}

























