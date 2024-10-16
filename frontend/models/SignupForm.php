<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app','Этот логин уже занят!')],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'match', 'pattern' => '/^\S+$/', 'message' => Yii::t('app', 'Логин не может содержать пробелы!')],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9@_\-\.]+$/', 'message' => Yii::t('app', 'Логин может содержать только латинские буквы!')],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['password', 'match', 'pattern' => '/^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};":\\|,.<>\/?]*$/', 'message' => Yii::t('app', 'Пароль может содержать только латинские буквы, цифры и специальные символы!')],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup($model)
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        $user->save(false);

        $model->user_id = $user->id;
        $model->save();

        return true;
    }
}
