<?php

namespace frontend\controllers;

use common\models\Payment;
use common\models\PaymentSearch;
use common\models\Purpose;
use common\models\Teacher;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
{
    /**
     * @inheritDoc
     */
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

    /**
     * Lists all Payment models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Load the existing purpose or create a new one if it doesn't exist
        $purpose = Purpose::find()->one() ?: new Purpose();

        // Check if form is submitted and try to load and save data
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
        $file = Payment::findOne($id);

        return Yii::$app->response->sendFile($file->payment, 'Квитанция.pdf', [
            'inline' => true, // This forces the file to open in the browser instead of downloading
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
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

    /**
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
