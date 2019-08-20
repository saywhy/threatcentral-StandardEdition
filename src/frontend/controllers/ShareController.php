<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Group;
use common\models\Share;
use common\models\ShareTag;
use common\models\Tag;
use common\models\Comment;
use Gufy\PdfToHtml\Pdf;
use Gufy\PdfToHtml\Html;
use PhpOffice\PhpWord\IOFactory;

/**
 * Share controller
 */
class ShareController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','detail','list','add','del','like','insert','update','read-file','add-file','add-comment','del-comment'],
                'rules' => [
                    [
                        'actions' => [''],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index','detail','list','add','like','insert','update','read-file','add-file','add-comment'],
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDetail($id)
    {
        $share = Share::readOne($id);
        if(empty($share)){
            throw new \yii\web\HttpException(404);
        }
        $share = ArrayHelper::toArray($share);
        return $this->render('detail',[
            'share' => $share,
        ]);
    }

    public function actionPage($page = 1,$rows = 15)
    {
        $this->isAPI();
        if(Yii::$app->request->isPost)
        {
            $post = json_decode(Yii::$app->request->getRawBody(),true);
            $page = empty($post['page']) ? $page : $post['page'];
            $rows = empty($post['rows']) ? $rows : $post['rows'];
            $wds = empty($post['wds']) ? [] : $post['wds'];
        }
        return Share::page($page,$rows,$wds);
    }

    public function actionList($offSet = 0,$limit = 10,$wds = [])
    {
        $this->isAPI();
        if(Yii::$app->request->isPost)
        {
            $post = json_decode(Yii::$app->request->getRawBody(),true);
            $offSet = empty($post['offSet']) ? $offSet : $post['offSet'];
            $limit = empty($post['limit']) ? $limit : $post['limit'];
            $wds = empty($post['wds']) ? [] : $post['wds'];
        }
        return Share::list($offSet,$limit,$wds);
    }

    public function actionAdd()
    {
        $GroupList = Group::find()->orderBy('level')->all();
        $GroupList = ArrayHelper::toArray($GroupList);

        $sql = 'SELECT tagName, COUNT(tagName) as count 
                FROM share_tag 
                GROUP BY tagName 
                ORDER BY count DESC 
                LIMIT 10';
        $TagTop = Yii::$app->db->createCommand($sql)->query();
        $TagTop = ArrayHelper::toArray($TagTop);

        $GroupList = Group::find()->orderBy('level')->all();
        $GroupList = ArrayHelper::toArray($GroupList);
        return $this->render('add',['GroupList'=>$GroupList,'TagTop'=>$TagTop]);
    }

    public function actionDel()
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
        $data['count'] = Share::del($post);
        $data['status'] = 'success';
        return $data;
    }

    public function actionLike()
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
        $share = Share::like($post);
        $data['status'] = 'success';
        $data['data'] = $share;
        return $data;
    }

    public function actionInsert()
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
        $share = Share::add($post);
        $data['id'] = $share->id;
        $data['status'] = 'success';
        return $data;
    }

    public function actionUpdate()
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
        $share = Share::findOne($post['id']);
        if(empty($share)){
            $data['status'] = 'fail';
            $data['errorMessage'] = 'This sharing does not exist.';
            return $data;
        }
        $share->data = $post['data'];
        $share->save();
        $data['id'] = $share->id;
        $data['status'] = 'success';
        return $data; 
    }

    public function actionReadFile()
    {
        $this->isAPI();
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            return $data;
        }
        $file_tmp = $_FILES['file'];

        switch ($file_tmp['type']) {
            case 'application/msword':
                $word = IOFactory::load($file_tmp['tmp_name'],'MsDoc');
                break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $word = IOFactory::load($file_tmp['tmp_name']);
                break;
            case 'application/pdf':
                $pdf = new pdf($file_tmp['tmp_name']);
                $num = (int)$pdf->getPages();
                $content = '';
                for($i = 1; $i <= $num; $i++) {
                    $content = $content.$pdf->html($i);
                }
                break;
            default:
                $data['status'] = 'fail';
                $data['errorMessage'] = 'Unknown file';
                return $data;
        }
        if(empty($content)){
            $xmlWriter = IOFactory::createWriter($word,'HTML');
            $content = $xmlWriter->getContent();
        }
        $content = preg_replace('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', '', $content);
        $data['status'] = 'success';
        $data['data'] = $content;
        return $data;
    }

    public function actionAddFile()
    {
        $this->isAPI();
        if(!Yii::$app->request->isPost)
        {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            return $data;
        }
        $file_tmp = $_FILES['file'];
        $uid = uniqid();
        $path = Yii::$app->params['staticPath'].'/share/'.$uid.'/';

        mkdir($path,0777,true);
        move_uploaded_file($file_tmp['tmp_name'],$path.$file_tmp['name']);
        
        $data['status'] = 'success';
        $data['path'] = '/share/'.$uid.'/'.$file_tmp['name'];
        return $data;
    }

    public function actionAddComment()
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
        $comment = Comment::add($post);
        $data['comment'] = ArrayHelper::toArray($comment);
        $data['status'] = 'success';
        return $data; 
    }

    public function actionDelComment()
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
        $data['count'] = Comment::del($post);
        $data['status'] = 'success';
        return $data; 
    }

    public function actionGetComment($offset = 0,$limit = 15)
    {
        $this->isAPI();
        if(Yii::$app->request->isPost)
        {
            $post = json_decode(Yii::$app->request->getRawBody(),true);
            $offset = empty($post['offset']) ? $offset : $post['offset'];
            $limit = empty($post['limit']) ? $limit : $post['limit'];
        }
        $data = Comment::list($offset,$limit,$post['sid']);
        $data['status'] =  'success';
        return $data;
    }

}


