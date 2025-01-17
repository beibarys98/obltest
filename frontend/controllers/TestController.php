<?php

namespace frontend\controllers;

use common\models\Admin;
use common\models\Answer;
use common\models\Certificate;
use common\models\File;
use common\models\Formula;
use common\models\Payment;
use common\models\Percentage;
use common\models\Purpose;
use common\models\Question;
use common\models\Result;
use common\models\ResultPdf;
use common\models\Teacher;
use common\models\TeacherAnswer;
use common\models\Test;
use common\models\TestTaker;
use DOMDocument;
use DOMXPath;
use kartik\mpdf\Pdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\ZipArchive;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        if(!Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

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

        $dataProvider5 = new ActiveDataProvider([
            'query' => Test::find()->andWhere(['status' => 'certificated'])
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'dataProvider3' => $dataProvider3,
            'dataProvider4' => $dataProvider4,
            'dataProvider5' => $dataProvider5,
        ]);
    }

    public function certificate($teacher, $test, $place)
    {
        $cert = Certificate::findOne(['subject_id' => $test->subject_id])->certificate;
        $imgPath = Yii::getAlias("@webroot/certificates/{$place}/{$cert}");
        $image = imagecreatefromjpeg($imgPath);
        $textColor = imagecolorallocate($image, 227, 41, 29);
        $fontPath = '/app/frontend/fonts/times.ttf';

        //wrinting name
        $averageCharWidth = 9.5;
        $numChars = strlen($teacher->name);
        $textWidth = $numChars * $averageCharWidth;
        $cx = 950;
        $x = (int)($cx - ($textWidth / 2));
        imagettftext($image, 28, 0, $x, 760, $textColor, $fontPath, $teacher->name);

        //writing number


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

    public function actionView($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $test = Test::findOne($id);
        $questions = Question::find()
            ->andWhere(['test_id' => $id])
            ->all();

        return $this->render('view', [
            'test' => $test,
            'questions' => $questions,
        ]);
    }

    public function actionCreate()
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $model = new Test();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->status = 'new';
                if (Yii::$app->request->isPost) {
                    $model->file = UploadedFile::getInstance($model, 'file');
                    $filePath = 'uploads/'
                        . Yii::$app->security->generateRandomString(8)
                        . '.'. $model->file->extension;

                    // Save the file to the specified path
                    if ($model->file->saveAs($filePath)) {
                        $model->test = $filePath;

                        //save startTime and endTime
                        $model->start_time = $model->date . ' 06:00:00';
                        $model->end_time = $model->date . ' 23:00:00';

                        $model->save(false);

                        $linesArray = $this->parseWordDocument($filePath);
                        $this->processAndStoreQuestions($linesArray, $model->id);

                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    function parseWordDocument($filePath)
    {
        // Extract XML content from the .docx file
        $zip = new ZipArchive;
        if ($zip->open($filePath) === TRUE) {
            $xmlContent = '';
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);
                if (strpos($entry, 'word/document.xml') !== false) {
                    $xmlContent = $zip->getFromIndex($i);
                    break;
                }
            }
            $zip->close();
        }

        // Load and parse XML content
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadXML($xmlContent);
        libxml_clear_errors();

        // Create XPath to find MathML elements
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('m', 'http://schemas.openxmlformats.org/officeDocument/2006/math');

        // Query for MathML elements and remove them
        $nodes = $xpath->query('//m:*');
        foreach ($nodes as $node) {
            $node->parentNode->removeChild($node);
        }

        // Save the modified XML content
        $modifiedXmlContent = $dom->saveXML();

        // Repackage the .docx file with the updated XML content
        $newFilePath = 'uploads/' . Yii::$app->security->generateRandomString(8) . '.docx';

        $tempFile = new ResultPdf();
        $tempFile->test_id = Test::findOne(['test' => $filePath])->id;
        $tempFile->path = $newFilePath;
        $tempFile->save(false);

        $newZip = new ZipArchive;
        if ($newZip->open($newFilePath, ZipArchive::CREATE) === TRUE) {
            $newZip->addFromString('word/document.xml', $modifiedXmlContent);

            // Add other necessary files from the original .docx
            $zip = new ZipArchive;
            if ($zip->open($filePath) === TRUE) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entry = $zip->getNameIndex($i);
                    if ($entry !== 'word/document.xml') {
                        $newZip->addFromString($entry, $zip->getFromIndex($i));
                    }
                }
                $zip->close();
            }

            $newZip->close();
        }

        $phpWord = IOFactory::load($newFilePath);
        $lines = [];

        // Loop through each section in the document
        foreach ($phpWord->getSections() as $section) {
            // Loop through each element in the section
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    $textLine = '';

                    // Process each element within the TextRun
                    foreach ($element->getElements() as $textElement) {
                        if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                            // Concatenate the text to form the full line
                            $textLine .= $textElement->getText();
                        }
                    }

                    // Add the text line with correct information to the array
                    $lines[] = [
                        'text' => $textLine,
                    ];
                }
            }
        }


        return $lines;
    }

    public function processAndStoreQuestions($linesArray, $test_id)
    {
        $currentQuestion = null;
        $firstAnswerProcessed = false;

        foreach ($linesArray as $lineData) {
            $lineText = $lineData['text'];

            if (preg_match('/^\s*\d+\s*\.?\s*(.+)$/u', $lineText, $matches)) {
                // Create a new question
                $currentQuestion = new Question();
                $currentQuestion->test_id = $test_id;
                $currentQuestion->question = $matches[1];
                $currentQuestion->correct_answer = ''; // Set this later if needed

                $currentQuestion->save();
                $firstAnswerProcessed = false;

            } elseif (preg_match('/^\s*[a-zA-Zа-яА-ЯёЁ]\s*[\.\)]?\s*(.+)$/u', $lineText, $matches)) {
                // This is an answer
                if ($currentQuestion !== null) {
                    $answerText = $matches[1];
                    $answer = new Answer();
                    $answer->question_id = $currentQuestion->id;
                    $answer->answer = $answerText;
                    $answer->save();

                    if (!$firstAnswerProcessed) {
                        $currentQuestion->correct_answer = $answer->id;
                        $firstAnswerProcessed = true;
                        $currentQuestion->save(false);
                    }
                }
            }
        }
    }


    public function actionReady($id): \yii\web\Response
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $test = Test::findOne($id);
        $test->status = 'ready';
        $test->save(false);

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionPublish($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $test = Test::findOne($id);
        $test->status = 'public';
        $test->save(false);

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionEnd($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        //save results in pdf
        $results = new ActiveDataProvider([
            'query' => Result::find()
                ->select(['teacher_id', 'result'])
                ->andWhere(['test_id' => $id])
                ->groupBy('teacher_id') // Group by teacher ID
                ->orderBy(['result' => SORT_DESC]),
            'pagination' => [
                'pageSize' => false,
            ],
        ]);
        $testDP = new ActiveDataProvider([
            'query' => Test::find()->andWhere(['id' => $id]),
        ]);

        $content = $this->renderPartial('result', [
            'results' => $results,
            'testDP' => $testDP,
        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'filename' => 'Нәтиже.pdf',
        ]);
        $pdfOutput = $pdf->render();
        $pdfFilePath = Yii::getAlias('@webroot/results/')
            . Yii::$app->security->generateRandomString(8)
            . '.pdf';
        file_put_contents($pdfFilePath, $pdfOutput);
        $result_pdf = new ResultPdf();
        $result_pdf->test_id = $id;
        $result_pdf->path = $pdfFilePath;
        $result_pdf->save(false);

        $test = Test::findOne($id);
        $test->status = 'finished';
        $test->save(false);

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionPresent($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $test = Test::findOne($id);
        $test->status = 'certificated';
        $test->save(false);

        //send certificates
        $topResults = Result::find()
            ->andWhere(['test_id' => $id])
            ->orderBy(['result' => SORT_DESC])
            ->all();
        $firstPlace = [];
        $secondPlace = [];
        $thirdPlace = [];
        $goodResults = [];
        $certificateResults = [];
        $percentage = Percentage::find()->one();
        foreach ($topResults as $result) {
            if ($result->result >= $percentage->first) {
                $firstPlace[] = $result;
            }
            else if ($result->result >= $percentage->second) {
                $secondPlace[] = $result;
            }
            else if ($result->result >= $percentage->third) {
                $thirdPlace[] = $result;
            }
            else if ($result->result >= $percentage->good) {
                $goodResults[] = $result;
            }
            else if ($result->result >= $percentage->participant) {
                $certificateResults[] = $result;
            }
        }
        foreach ($firstPlace as $result) {
            $this->certificate(Teacher::findOne($result->teacher_id), Test::findOne($id), 1);
        }
        foreach ($secondPlace as $result) {
            $this->certificate(Teacher::findOne($result->teacher_id), Test::findOne($id), 2);
        }
        foreach ($thirdPlace as $result) {
            $this->certificate(Teacher::findOne($result->teacher_id), Test::findOne($id), 3);
        }
        foreach ($goodResults as $result) {
            $this->certificate(Teacher::findOne($result->teacher_id), Test::findOne($id), 4);
        }
        foreach ($certificateResults as $result) {
            $this->certificate(Teacher::findOne($result->teacher_id), Test::findOne($id), 5);
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionDownloadZip($type, $id){
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        if($type == 'receipts'){
            $filePaths = Payment::find()
                ->andWhere(['test_id' => $id])
                ->select('payment')
                ->all();
            $zipFileName = 'Квитанциялар.zip';
        }elseif($type == 'certificates'){
            $filePaths = File::find()
                ->andWhere(['test_id' => $id])
                ->andWhere(['like', 'path', '%.jpeg', false])
                ->select('path')
                ->all();
            $zipFileName = 'Сертификаттар.zip';
        }elseif ($type == 'reports') {
            $filePaths = File::find()
                ->andWhere(['test_id' => $id])
                ->andWhere(['like', 'path', '%.pdf', false])
                ->select('path')
                ->all();
            $zipFileName = 'Қатемен Жұмыстар.zip';
        }else{
            $filePaths = ResultPdf::find()
                ->andWhere(['like', 'path', '%.pdf', false])
                ->select('path')
                ->all();
            $zipFileName = 'Нәтижелер.zip';
        }
        $zip = new \ZipArchive();
        $zipFilePath = Yii::getAlias('@webroot/uploads/' . $zipFileName);
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            throw new HttpException(500, 'Could not create ZIP file.');
        }
        foreach ($filePaths as $filePath) {
            if($type == 'receipts'){
                $realPath = Yii::getAlias($filePath->payment);
            }else{
                $realPath = $filePath->path;
            }
            if (file_exists($realPath)) {
                $zip->addFile($realPath, basename($realPath));
            } else {
                // Handle the error if the file does not exist
                Yii::error("File not found: $realPath");
            }
        }
        $zip->close();
        return Yii::$app->response->sendFile($zipFilePath)->on(\yii\web\Response::EVENT_AFTER_SEND, function () use ($zipFilePath) {
            @unlink($zipFilePath); // Delete the ZIP file after sending it
        });
    }

    public function actionResult($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $file = ResultPdf::find()
            ->andWhere(['test_id' => $id])
            ->andWhere(['like', 'path', '%.pdf', false])
            ->one();
        return Yii::$app->response->sendFile($file->path, 'Нәтиже.pdf', ['inline' => true,]);
    }

    public function actionAddFormula($id, $type)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        if($type == 'question'){
            $model = Question::findOne($id);
            $tid = $model->test_id;
        }else{
            $model = Answer::findOne($id);
            $tid = $model->question->test_id;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (Yii::$app->request->isPost) {
                    $model->file = UploadedFile::getInstance($model, 'file');

                    $filePath = 'formulas/'
                        . Yii::$app->security->generateRandomString(8)
                        . '.'. $model->file->extension;

                    // Save the file to the specified path
                    if ($model->file->saveAs($filePath)) {
                        $model->formula = $filePath;
                        $model->save(false);

                        return $this->redirect(['view', 'id' => $tid]);
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('add-formula', [
            'model' => $model
        ]);
    }

    public function actionDeleteFormula($id, $test_id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        return $this->redirect(['formula', 'id' => $test_id]);
    }

    public function actionSettings()
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $percentage = Percentage::find()->one();

        if ($percentage->load(Yii::$app->request->post()) && $percentage->save()) {
            return $this->redirect(['index']);
        }

        $purpose = Purpose::find()->one() ?: new Purpose();

        if ($purpose->load(Yii::$app->request->post()) && $purpose->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('settings', [
            'percentage' => $percentage,
            'purpose' => $purpose,
        ]);
    }

    public function actionDelete($id)
    {
        if(Yii::$app->user->isGuest || !Admin::findOne(['user_id' => Yii::$app->user->identity->id])){
            return $this->redirect(['/site/login']);
        }

        $test = Test::findOne($id);
        $files = File::find()->andWhere(['test_id' => $id])->all();
        $payments = Payment::find()->andWhere(['test_id' => $id])->all();
        $questions = Question::find()->where(['test_id' => $id])->all();
        $results = Result::find()->andWhere(['test_id' => $id])->all();
        $resultPdfs = ResultPdf::find()->andWhere(['test_id' => $id])->all();
        $testTakers = TestTaker::find()->andWhere(['test_id' => $id])->all();

        foreach ($files as $file) {
            unlink($file->path);
            $file->delete();
        }

        foreach ($payments as $payment) {
            if (file_exists($payment->payment)) {
                unlink($payment->payment);
                $payment->delete();
            }
        }

        foreach ($questions as $question) {
            $answers = Answer::find()->andWhere(['question_id' => $question->id])->all();
            foreach ($answers as $a) {
                if($a->formula){
                    if(file_exists($a->formula)){
                        unlink($a->formula);
                    }
                }
                $a->delete();
            }
            $teacherAnswers = TeacherAnswer::find()->andWhere(['question_id' => $question->id])->all();
            foreach ($teacherAnswers as $tA) {
                $tA->delete();
            }
            if($question->formula){
                if(file_exists($question->formula)){
                    unlink($question->formula);
                }
            }
            $question->delete();
        }

        foreach ($results as $result) {
            $result->delete();
        }

        foreach ($resultPdfs as $rP) {
            if(file_exists($rP->path)){
                unlink($rP->path);
            }
            $rP->delete();
        }

        foreach ($testTakers as $tT) {
            $tT->delete();
        }

        if(unlink($test->test)){
            $test->delete();
        }

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
