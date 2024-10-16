<?php

namespace frontend\controllers;

use common\models\Admin;
use common\models\File;
use common\models\Teacher;
use common\models\Test;
use common\models\TestTaker;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class TestTakerController extends Controller
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

    public function actionIndex($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $test = Test::findOne($id);
        $dataProvider = new ActiveDataProvider([
            'query' => TestTaker::find()->where(['test_id' => $id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'test' => $test,
        ]);
    }

    public function actionDownload($id, $type)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $teacher = Teacher::findOne($id);

        if ($type == 'pdf') {
            $file = File::find()
                ->andWhere(['teacher_id' => $teacher->id])
                ->andWhere(['like', 'path', '%.pdf', false])
                ->one();
            $text = 'Қатемен жұмыс.pdf';
        }
        else {
            $file = File::find()
                ->andWhere(['teacher_id' => $teacher->id])
                ->andWhere(['like', 'path', '%.jpeg', false])
                ->one();
            $text = 'Сертификат.jpeg';
        }

        return Yii::$app->response->sendFile($file->path, $text, ['inline' => true]);
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

        $model = new TestTaker();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = TestTaker::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
