<?php


namespace common\models;


use yii\base\Model;

class UserOptionForm extends Model
{
    /**
     * Change password
     */
    public $old_password;
    public $new_password;
    public $repeat_password;


    /**
     * Change username
     */
    public $new_username;

    /**
     * Scenarios
     */
    const SCENARIO_CHANGE_PASSWORD = "change_password";
    const SCENARIO_CHANGE_USERNAME = "change_username";

    public function rules()
    {
        return [
            [["new_password", "repeat_password"], "string", "min" => 8],
            [["new_username"], "required", "on" => self::SCENARIO_CHANGE_USERNAME],
            ["new_username", "validateUsername", "on" => self::SCENARIO_CHANGE_USERNAME],
            [["old_password", "repeat_password", "new_password"], "required", "on" => self::SCENARIO_CHANGE_PASSWORD],
            ["old_password", "validatePassword"],
            ["repeat_password", "compare", "compareAttribute" => "new_password", "message" => "passwords dont match", "on" => self::SCENARIO_CHANGE_PASSWORD],
        ];
    }

    public function attributeLabels()
    {
        return [
            "old_password" => "Your password",
            "new_password" => "Your new password",
            "repeat_password" => "Type one more time",
            "new_username" => "Choose new username"
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = \Yii::$app->user->identity;
            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, "Incorrect Password!");
            }
        }
    }

    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = \Yii::$app->user->identity;
            $raw = User::findAll(["username" => $this->new_username]);
            if (!$user || !empty($raw)) {
                $this->addError($attribute, "This username already exist!");
            }
        }
    }


    public function changePassword()
    {
        /* @var $user User */
        $user = \Yii::$app->user->identity;
        $user->password = $this->new_password;
        $user->update();
    }



    public function changeUsername()
    {
        /* @var $user User */
        $user = \Yii::$app->user->identity;
        $user->username = $this->new_username;
        $user->update();
    }

}

