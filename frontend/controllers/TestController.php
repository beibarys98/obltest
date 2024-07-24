<?php

namespace frontend\controllers;

use common\models\File;
use common\models\Formula;
use common\models\Question;
use common\models\Result;
use common\models\ResultPdf;
use common\models\Teacher;
use common\models\Test;
use common\models\TestSearch;
use DateTime;
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
        $test = Test::find()->andWhere(['status' => 'public'])->all();
        foreach ($test as $test) {
            if (new DateTime() >= new DateTime($test->end_time)) {
                $test->status = 'finished';
                $test->save(false);
            }
        }

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
        $test = Test::findOne($id);
        if (new DateTime() >= new DateTime($test->end_time)) {
            $test->status = 'finished';
            $test->save(false);
        }
        $questions = Question::find()->andWhere(['test_id' => $id])->all();

        return $this->render('view', [
            'test' => $test,
            'questions' => $questions,
        ]);
    }

    public function actionCreate()
    {
        $model = new Test();

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

        //save results pdf in db
        $pdfOutput = $pdf->render();
        $pdfFilePath = Yii::getAlias('@webroot/results/')
            . Yii::$app->security->generateRandomString(8)
            . '.pdf';
        file_put_contents($pdfFilePath, $pdfOutput);

        $result_pdf = new ResultPdf();
        $result_pdf->test_id = $id;
        $result_pdf->path = $pdfFilePath;
        $result_pdf->save(false);

        //send certificates
        $topResults = Result::find()
            ->andWhere(['test_id' => $id])
            ->orderBy(['result' => SORT_DESC])
            ->all();
        $firstPlace = $topResults[0] ?? null;
        $secondPlace = $topResults[1] ?? null;
        $thirdPlace = $topResults[2] ?? null;

        if ($firstPlace) {
            $this->certificate(Teacher::findOne($firstPlace->teacher_id), Test::findOne($id), 1);
        }
        if ($secondPlace) {
            $this->certificate(Teacher::findOne($secondPlace->teacher_id), Test::findOne($id), 2);
        }
        if ($thirdPlace) {
            $this->certificate(Teacher::findOne($thirdPlace->teacher_id), Test::findOne($id), 3);
        }

        if (count($topResults) >= 4) {
            $remainingResults = array_slice($topResults, 3);
            foreach ($remainingResults as $result) {
                $teacher = Teacher::findOne($result->teacher_id);
                $test = Test::findOne($id);
                $this->certificate($teacher, $test, 4);
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionResult($id)
    {
        $file = ResultPdf::findOne(['test_id' => $id]);
        return Yii::$app->response->sendFile($file->path, 'Нәтиже.pdf');
    }

    public function certificate($teacher, $test, $place)
    {
        $imgPath = Yii::getAlias("@webroot/certificates/certificate{$place}.jpeg");
        $image = imagecreatefromjpeg($imgPath);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        $textColor2 = imagecolorallocate($image, 43, 56, 98);
        $fontPath = '/app/frontend/fonts/times.ttf';
        imagettftext($image, 24, 0, 525, 585, $textColor, $fontPath, $teacher->name);
        imagettftext($image, 24, 0, 450, 450, $textColor2, $fontPath, $teacher->subject->subject);
        $month = date('n');
        $year = date('Y');
        $months = [
            1 => 'қаңтар', 2 => 'ақпан', 3 => 'наурыз',
            4 => 'сәуір', 5 => 'мамыр', 6 => 'маусым',
            7 => 'шілде', 8 => 'тамыз', 9 => 'қыркүйек',
            10 => 'қазан', 11 => 'қараша', 12 => 'желтоқсан',
        ];
        $monthName = $months[$month];
        $dateText = $monthName . ' ' . $year . ' жыл';
        imagettftext($image, 16, 0, 650, 830, $textColor2, $fontPath, $dateText);
        $newPath = Yii::getAlias('@webroot/certificates/')
            . Yii::$app->security->generateRandomString(8)
            . '.jpeg';
        imagejpeg($image, $newPath);
        imagedestroy($image);

        $certificate = new File();
        $certificate->teacher_id = $teacher->id;
        $certificate->test_id = $test->id;
        $certificate->path = $newPath;
        $certificate->save(false);
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
        $test = Test::findOne($id);
        $questions = Question::find()->where(['test_id' => $id])->all();
        foreach ($questions as $question) {
            $formulas = Formula::find()->andWhere(['question_id' => $question->id])->all();
            foreach ($formulas as $formula){
                unlink($formula->path);
                $formula->delete();
            }
            $question->delete();
        }
        $results = Result::find()->andWhere(['test_id' => $id])->all();
        foreach ($results as $result){
            $result->delete();
        }
        $test->status = 'deleted';
        $test->save(false);

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
