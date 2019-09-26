<?php


namespace frontend\controllers;


use common\classes\ConsoleLog;
use common\models\CheckList;
use common\models\CheckListItem;
use common\models\User;
use common\models\UserOptionForm;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\helpers\Console;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
use yii\web\Response;

class UserController extends BaseController
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

    /**
     * Profile page of user
     * @return string
     */
    public function actionProfile()
    {
        return $this->render("profile", ["user" => Yii::$app->user->identity]);
    }

    /**
     * Form actions for changing user's password
     * @return array|string|Response
     */
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
            Yii::$app->session->setFlash("success", "Password updated", true);
            return $this->redirect(Url::to("/user/profile"));
        } else {
            return $this->renderAjax("change_password", ["user" => Yii::$app->user->identity, "model" => $model]);
        }
    }

    /**
     * Form actions for changing user's name
     * @return array|string|Response
     */
    public function actionChangeName()
    {
        $this->layout = false;
        $model = new UserOptionForm();
        $this->layout = false;
        $model->setScenario($model::SCENARIO_CHANGE_USERNAME);
        //Ajax validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->changeUsername();
            Yii::$app->session->setFlash("success", "Username updated", true);
            return $this->redirect(Url::to("/user/profile"));
        } else {
            return $this->renderAjax("change_name", ["user" => Yii::$app->user->identity, "model" => $model]);
        }
    }

    /**
     * View page of all checklists
     * @param null $id
     * @return string
     */
    public function actionChecklists($id = null)
    {
        $layout = $this->layout;
        $query = CheckList::find();
        $dataProvider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => 10
            ]
        ]);
        if (isset($id)) {
            $query_items = CheckListItem::find()->where(["cl_id" => $id]);
            $cl = CheckList::findOne(["id" => $id]);
            $dataProviderItems = new ActiveDataProvider([
                "query" => $query_items,
                "sort" => ["attributes" => [
                    "id"
                ]],
                "pagination" => [
                    "pageSize" => 10
                ]
            ]);
            $this->layout = false;
            return $this->render("checklist_items", ["dataProvider" => $dataProviderItems, "cl_name" => $cl->name]);
        }
        $this->layout = $layout;
        return $this->render("checklists", ["dataProvider" => $dataProvider]);
    }

    /**
     * @param null $id
     * @return string
     */
    public function actionMyCl($id = null)
    {
        $layout = $this->layout;
        $data = Yii::$app->request->post();
        $query = CheckList::find()->where(["user_id" => Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => 10
            ]
        ]);
        if (isset($id)) {
            $query_items = CheckListItem::find()->where(["cl_id" => $id]);
            $cl = CheckList::findOne(["id" => $id]);
            $dataProviderItems = new ActiveDataProvider([
                "query" => $query_items,
                "sort" => ["attributes" => [
                    "id"
                ]],
                "pagination" => [
                    "pageSize" => 10
                ]
            ]);
            $this->layout = false;
            return $this->render("my_checklist_items", ["dataProvider" => $dataProviderItems, "cl" => $cl]);
        }

        $this->layout = $layout;
        return $this->render("my_cl", ["dataProvider" => $dataProvider]);
    }

    /**
     * Form for creating checklist
     * @return string|Response
     */
    public function actionChecklistForm()
    {
        $layout = $this->layout;
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            if ($data["name"] != "") {
                $cl = new CheckList();
                $cl->user_id = Yii::$app->user->id;
                $cl->name = $data["name"];
                $cl->save();
                $cl->saveItems($data["items"]);
            }
            return $this->redirect(\Yii::$app->request->referrer);
        }
        $user = Yii::$app->user->identity;
        $this->layout = false;
        return $this->render("checklist_form", ["user" => $user]);
    }

    /**
     * Form for submit deleting checklist
     * @param null $id
     * @param null $del_id
     * @return string|Response
     */
    public function actionDeleteCl($id = null, $del_id = null)
    {
        $this->layout = false;
        if (isset($del_id)) {
            CheckList::deleteAll(["id" => $del_id]);
        }
        return $this->render("delete", ["del_id" => $id]);

    }

    /**
     * Update CheckList
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionMyClUpd()
    {
        $data = Yii::$app->request->post();
        $cl_item = CheckListItem::findOne($data["item_id"]);
        $cl_item->done = ($data["value"] =="true") ? "1":"0";
        $cl_item->update();
        $raw = CheckListItem::findone(["done"=>"0"]);
        $cl = CheckList::findOne(["id"=>$data["cl_id"]]);
        if(!isset($raw)){
            $cl->done = "1";
        }else {
            $cl->done = "0";
        }
        $cl->update();
        return $this->redirect(null,200);

    }
}