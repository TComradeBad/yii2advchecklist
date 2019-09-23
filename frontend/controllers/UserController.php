<?php


namespace frontend\controllers;


use common\models\User;
use common\models\UserOptionForm;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
use yii\web\Response;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            "access" => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        "roles" => ["@"],
                        "allow" => true,
                    ]
                ]
            ]
        ];
    }


    public function actionProfile()
    {
        return $this->render("profile", ["user" => Yii::$app->user->identity]);
    }

    public function actionMyCl()
    {
        return $this->render("my_cl", ["user" => Yii::$app->user->identity]);
    }

    public function actionChangePassword()
    {
        $model = new UserOptionForm();
        $this->layout = false;
        $model->setScenario($model::SCENARIO_CHANGE_PASSWORD);

        //Ajax validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->changePassword();
            Yii::$app->session->setFlash("success","Password updated");
            return $this->redirect(Url::to("/user/profile"));
        } else {
            return $this->renderAjax("change_password", ["user" => Yii::$app->user->identity, "model" => $model]);
        }
    }

    public function actionChangeName($upd_id = null)
    {
        $this->layout = false;
        if (isset($upd_id)) {

        }
        return $this->render("change_name", ["user" => Yii::$app->user->identity]);
    }


}