<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Alert;
use common\models\Config;





/**
 * Site controller
 */
class AlertController extends Controller
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
                    'actions' => ['index','detail','page','top'],
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
                'only' => ['index','detail','page','top','del'],
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

    public function actionDetail($id)
    {
        $alert = Alert::findOne($id);
        if(empty($alert)){
            throw new \yii\web\HttpException(404);
        }
        $alert = ArrayHelper::toArray($alert);
        return $this->render('detail',[
            'alert' => $alert,
        ]);
    }

    private function page($page = 1,$rows = 15,$whereList = [])
    {
        $query = Alert::find();
        foreach ($whereList as $key => $value) {
            $query = $query->andWhere($value);
        }
        $query = $query->orderBy([
                'time' => SORT_DESC,
                'id' => SORT_DESC,
            ]);
        $page = (int)$page;
        $rows = (int)$rows;
        $count = (int)$query->count();
        $maxPage = ceil($count/$rows);
        $page = $page>$maxPage ? $maxPage : $page;
        $pageData = $query->offSet(($page-1)*$rows)->limit($rows)->all();

        $data = [
            'data' => ArrayHelper::toArray($pageData),
            'count' => $count,
            'maxPage' => $maxPage,
            'pageNow' => $page,
            'status' => 'success',
        ];
        return $data;
    }

    private function getWhereList($arr)
    {
        $whereList = [];
        if(isset($arr['client_ip']) && $arr['client_ip'] != ''){
            $whereList[] = ['like', 'client_ip', $arr['client_ip']];
        }
        if(isset($arr['startTime'])){
            $whereList[] = ['>', 'time', $arr['startTime']];
        }
        if(isset($arr['endTime'])){
            $whereList[] = ['<', 'time', $arr['endTime']];
        }
        if(isset($arr['indicator']) && $arr['indicator'] != ''){
            $whereList[] = ['indicator' => $arr['indicator']];
        }
        return $whereList;
    }


    public function actionPage($page = 1,$rows = 15)
    {
        $this->isAPI();
        $whereList = [];
        if(Yii::$app->request->isPost)
        {
            $post = json_decode(Yii::$app->request->getRawBody(),true);
            $page = empty($post['page']) ? $page : $post['page'];
            $rows = empty($post['rows']) ? $rows : $post['rows'];
            $whereList = $this->getWhereList($post);
        }
        return $this->page($page,$rows,$whereList);
    }

    public function actionDownloadCsv($client_ip = '',$startTime = false,$endTime = false){
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        if($startTime === false){
            $startTime = mktime(0,0,0,$month,$day,$year);
        }
        if($endTime === false){
            $endTime = mktime(23,59,59,$month,$day,$year);
        }
        $arr = [
            'client_ip' => $client_ip,
            'startTime' => $startTime,
            'endTime' => $endTime
        ];
        $whereList = $this->getWhereList($arr);

        $query = Alert::find()->select(['client_ip','indicator','category','popularity','threat_name','source','targets','first_seen','count(*) as count']);
        foreach ($whereList as $key => $value) {
            $query = $query->andWhere($value);
        }
        $query = $query->groupBy(['indicator','client_ip']);
        $query = $query->orderBy([
                'count' => SORT_DESC,
            ]);

        $downloadData = $query->asArray()->all();
        $EXCEL_OUT=iconv('UTF-8','GBK',"失陷主机,指标,威胁类型,流行度,威胁名称,威胁来源,受害区域,全球威胁起始时间,告警数\n");
        foreach ($downloadData as $key => $item) {
            try
            {
                $line = iconv('UTF-8','GBK//IGNORE',
                    $item['client_ip'].','.
                    $item['indicator'].','.
                    $item['category'].','.
                    $item['popularity'].','.
                    $item['threat_name'].','.
                    $item['source'].','.
                    "\"".$item['targets']."\"".','.
                    $item['first_seen'].','.
                    $item['count'].
                    "\n"
                );
            }
            catch(Exception $e)
            {
                break;
            }
            $EXCEL_OUT.=$line;
        }
        $headers = Yii::$app->response->headers;
        $headers->set('Cache-Control','max-age=0');
        $startTimeStr = date("Y-m-d", $startTime);
        $endTimeStr = date("Y-m-d", $endTime);
        $downloadName = $startTimeStr == $endTimeStr ? $startTimeStr : ($startTimeStr.' '.$endTimeStr);
        $headers->set('Content-Disposition',"attachment;filename=".$downloadName.".csv");
        return $EXCEL_OUT;
    }

    public function actionDel($page = 1,$rows = 15)
    {
        $this->isAPI();
        if(Yii::$app->request->isPost)
        {
            $post = json_decode(Yii::$app->request->getRawBody(),true);
            $page = empty($post['page']) ? $page : $post['page'];
            $rows = empty($post['rows']) ? $rows : $post['rows'];
            
        }
        Alert::deleteAll(['id' => $post['id']]);
        return $this->page($page,$rows);
    }

    public function actionTop()
    {
        $this->isAPI();
        $sql = 'SELECT device_ip, COUNT(device_ip) as count 
                FROM alert 
                GROUP BY device_ip 
                ORDER BY count DESC 
                LIMIT 10';
        $data = Yii::$app->db->createCommand($sql)->query();
        $data = ArrayHelper::toArray($data);
        $ret['status'] = 'success';
        $ret['data'] = $data;
        return $ret;
    }

}


