<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use common\models\UserLog;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $password;
    public $repassword;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required','message'=>'用户名不能为空'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '此用户已存在'],
            ['username', 'lengthUsername'],

            ['repassword', 'required','message'=>'密码不能为空'],
            ['repassword', 'equal'],

            ['password', 'required','message'=>'密码不能为空'],
            ['password', 'complexPassword'],
        ];
    }

    public function equal($attribute, $params)
    {
        
        if($this->password !== $this->repassword)
        {
            $this->addError($attribute, '密码不一致。');
        }
    }

    public function complexPassword($attribute, $params)
    {
        $len = strlen($this->password);
        if($len > 30 || $len < 8){
            $this->addError($attribute, '请输入8-30位密码。');
            return;
        }
        $isMatched = preg_match('/(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z0-9]).{8,30}/', $this->password, $matches);
        if($isMatched == 0)
        {
            $this->addError($attribute, '密码必须包含大写字母、小写字母、数字、特称字符。');
        }

    }

    public function lengthUsername($attribute, $params)
    {
        $len = strlen($this->username);
        if($len > 16 || $len < 2)
        {
            $this->addError($attribute, '请输入2-16位用户名。');
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        $userLog = new UserLog();
        $userLog->username = $this->username;
        $userLog->type = UserLog::Type_Signup;

        if (!$this->validate()) {
            $userLog->status = UserLog::Fail;
            $userLog->info = 'Failed register the first administrator';
            $userLog->password = $this->password;
            $userLog->save();
            return null;
        }
        $userLog->status = UserLog::Success;
        $userLog->info = 'Successfully register the first administrator';
        $userLog->save();

        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->role = 'admin';
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
