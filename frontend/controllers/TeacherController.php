<?php

namespace frontend\controllers;

use common\models\Admin;
use common\models\Teacher;
use common\models\TeacherSearch;
use common\models\User;
use frontend\models\SignupForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class TeacherController extends Controller
{

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $model = new SignupForm();
        $model2 = new Teacher();
        if ($model->load(Yii::$app->request->post())
            && $model2->load(Yii::$app->request->post())
            && $model->signup($model2)) {

            $model2->save(false);
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'model2' => $model2,
        ]);
    }

    public function actionUpdate($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $model2 = $this->findModel($id);
        $model = User::findOne(['id' => $model2->user_id]);

        if ($this->request->isPost
            && $model2->load($this->request->post())
            && $model->load($this->request->post())
            && $model2->save()) {

            if($model->password != null){
                $model->setPassword($model->password);
            }
            $model->save(false);

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model2' => $model2,
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $teacher = $this->findModel($id);
        $user = User::findOne(['id' => $teacher->user_id]);
        $teacher->delete();
        $user->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Teacher::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
