<?php

namespace frontend\controllers;

use common\models\File;
use common\models\Formula;
use common\models\Question;
use common\models\Result;
use common\models\Test;
use common\models\TestSearch;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * TestController implements the CRUD actions for Test model.
 */
class TestController extends Controller
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
        $dataProvider = new ActiveDataProvider([
            'query' => Test::find()->andWhere(['status' => 'new']),
        ]);

        $dataProvider2 = new ActiveDataProvider([
            'query' => Test::find()->andWhere(['status' => 'ready'])
        ]);

        $dataProvider3 = new ActiveDataProvider([
            'query' => Test::find()->andWhere(['status' => 'public'])
        ]);

        $dataProvider4 = new ActiveDataProvider([
            'query' => Test::find()->andWhere(['status' => 'finished'])
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'dataProvider3' => $dataProvider3,
            'dataProvider4' => $dataProvider4,
        ]);
    }

    public function actionView($id)
    {
        $questions = Question::find()->andWhere(['test_id' => $id])->all();

        return $this->render('view', [
            'test' => $this->findModel($id),
            'questions' => $questions,
        ]);
    }

    public function actionCreate()
    {
        $model = new Test();
        $questions = [];

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->status = 'new';
                $model->save(false);

                $lines = explode("\n", $model->test);

                $number = 1;
                foreach ($lines as $line) {
                    $data = explode("\t", $line); // Split each line by tabs to get question and answers

                    if (count($data) == 6) { // Ensure there are at least 5 elements (question, 4 answers, correct answer)
                        $questionModel = new Question();
                        $questionModel->test_id = $model->id;
                        $questionModel->number = $number;
                        $questionModel->question = trim($data[0]);
                        $questionModel->answer1 = trim($data[1]);
                        $questionModel->answer2 = trim($data[2]);
                        $questionModel->answer3 = trim($data[3]);
                        $questionModel->answer4 = trim($data[4]);
                        $questionModel->correct_answer = trim($data[5]);

                        $questionModel->save();

                        $number++;
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionReady($id){
        $test = Test::findOne($id);
        $test->status = 'ready';
        $test->save(false);

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionPublish($id){
        $test = Test::findOne($id);
        $test->status = 'public';
        $test->save(false);

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionEnd($id){
        $test = Test::findOne($id);
        $test->status = 'finished';
        $test->save(false);

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionResult($id)
    {
        $results = new ActiveDataProvider([
            'query' => Result::find()
                ->andWhere(['test_id' => $id])
                ->orderBy(['result' => SORT_DESC]),
        ]);

        //save results in pdf
        $content = $this->renderPartial('result', [
            'results' => $results
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'filename' => 'Нәтиже.pdf',
        ]);

        return $pdf->render();
    }

    public function actionFormula($id){
        $test = Test::findOne($id);
        $questions = Question::find()->andWhere(['test_id' => $id])->all();

        return $this->render('formula', [
            'test' => $test,
            'questions' => $questions,
        ]);
    }

    public function actionAddFormula($id, $t){
        $question = Question::findOne($id);
        $formula = new Formula();

        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstance($formula, 'file');
            $filePath = Yii::getAlias('@web') .'formulas/'
                . Yii::$app->security->generateRandomString(8) . '.' . $file->extension;
            if ($file->saveAs($filePath)) {
                $formula->question_id = $id;
                $formula->type = $t;
                $formula->path = $filePath;
                $formula->save(false);
                return $this->redirect(['formula', 'id' => $question->test_id]);
            }
        }

        return $this->render('add-formula', [
            'question' => $question,
            'type' => $t,
            'formula' => $formula,
        ]);
    }

    public function actionDeleteFormula($id, $test_id){
        $formula = Formula::findOne($id);
        unlink($formula->path);
        $formula->delete();
        return $this->redirect(['formula', 'id' => $test_id]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model2 = Question::find()->andWhere(['test_id' => $id])->all();

        $dataArray = Yii::$app->request->post('data');

        if (!empty($dataArray)) {
            foreach ($dataArray as $id => $value) {
                $questionModel = Question::findOne($id);
                if ($questionModel) {
                    $questionModel->question = $value['question'];
                    $questionModel->answer1 = $value['answer1'];
                    $questionModel->answer2 = $value['answer2'];
                    $questionModel->answer3 = $value['answer3'];
                    $questionModel->answer4 = $value['answer4'];
                    $questionModel->correct_answer = $value['correct_answer'];
                    $questionModel->save(false);
                }
            }
            if ($this->request->isPost && $model->load($this->request->post())) {
                $model->save(false);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'model2' => $model2,
        ]);
    }

    public function actionDelete($id)
    {
        $questions = Question::find()->where(['test_id' => $id])->all();
        foreach ($questions as $question) {
            $question->delete();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Test::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
