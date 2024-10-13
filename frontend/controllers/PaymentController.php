<?php

namespace frontend\controllers;

use common\models\Admin;
use common\models\Payment;
use common\models\PaymentSearch;
use common\models\Purpose;
use common\models\Teacher;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class PaymentController extends Controller
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

        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $purpose = Purpose::find()->one() ?: new Purpose();

        if ($purpose->load(Yii::$app->request->post()) && $purpose->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'purpose' => $purpose,
        ]);
    }

    public function actionPay($id){
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }

        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->identity->id]);
        $payment = new Payment();
        $payment->teacher_id = $teacher->id;
        $payment->test_id = $id;

        $purpose = Purpose::find()->one();

        if (Yii::$app->request->isPost) {
            $payment->file = UploadedFile::getInstance($payment, 'file');

            if ($payment->file && $payment->validate()) {
                // Save the file path into the payment field
                $filePath = 'payments/'
                    . Yii::$app->security->generateRandomString(8) . '.'
                    . $payment->file->extension;
                if ($payment->file->saveAs($filePath)) {
                    $payment->payment = $filePath;
                    $payment->created_at = date('Y-m-d H:i:s');
                    if ($payment->save(false)) {
                        return $this->redirect(['site/index']);
                    }
                }
            }
        }

        return $this->render('pay', [
            'teacher' => $teacher,
            'purpose' => $purpose,
            'payment' => $payment,
        ]);
    }

    public function actionReceipt($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $file = Payment::findOne($id);

        return Yii::$app->response->sendFile($file->payment, 'Квитанция.pdf', [
            'inline' => true, // This forces the file to open in the browser instead of downloading
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

        $model = new Payment();

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
        if (($model = Payment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
