<?php

namespace frontend\controllers;

use common\models\Answer;
use common\models\File;
use common\models\Payment;
use common\models\Question;
use common\models\Result;
use common\models\ResultPdf;
use common\models\StartTime;
use common\models\Teacher;
use common\models\Test;
use DateTime;
use DOMDocument;
use DOMXPath;
use kartik\mpdf\Pdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\ZipArchive;
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
        $model = new Test();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->status = 'new';

                if (Yii::$app->request->isPost) {
                    $model->file = UploadedFile::getInstance($model, 'file');

                    if ($model->validate()) {
                        $filePath = 'uploads/'
                            . Yii::$app->security->generateRandomString(8)
                            . '.'. $model->file->extension;

                        // Save the file to the specified path
                        if ($model->file->saveAs($filePath)) {
                            $model->test = $filePath;
                            $model->save(false);

                            $linesArray = $this->parseWordDocument($filePath);
                            $this->processAndStoreQuestions($linesArray, $model->id);

                            return $this->redirect(['view', 'id' => $model->id]);
                        }
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
                    $isBold = false;

                    // Process each element within the TextRun
                    foreach ($element->getElements() as $textElement) {
                        if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                            // Check if the text is bold by accessing the font style
                            $fontStyle = $textElement->getFontStyle();
                            if ($fontStyle && $fontStyle->isBold()) {
                                $isBold = true;
                            }

                            // Concatenate the text to form the full line
                            $textLine .= $textElement->getText();
                        } elseif ($textElement instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            // Process nested TextRun elements
                            foreach ($textElement->getElements() as $nestedTextElement) {
                                if ($nestedTextElement instanceof \PhpOffice\PhpWord\Element\Text) {
                                    // Check if the text is bold by accessing the font style
                                    $fontStyle = $nestedTextElement->getFontStyle();
                                    if ($fontStyle && $fontStyle->isBold()) {
                                        $isBold = true;
                                    }

                                    // Concatenate the text to form the full line
                                    $textLine .= $nestedTextElement->getText();
                                }
                            }
                        }
                    }

                    // Add the text line with bold information to the array
                    $lines[] = [
                        'text' => $textLine,
                        'isBold' => $isBold
                    ];
                } elseif ($element instanceof \PhpOffice\PhpWord\Element\Text) {
                    // Handle cases where the element is a single text element
                    $fontStyle = $element->getFontStyle();
                    $isBold = $fontStyle && $fontStyle->isBold();

                    $lines[] = [
                        'text' => $element->getText(),
                        'isBold' => $isBold
                    ];
                }
            }
        }

        return $lines;
    }

    public function processAndStoreQuestions($linesArray, $test_id)
    {
        $currentQuestion = null;
        foreach ($linesArray as $lineData) {
            $lineText = $lineData['text'];
            $isBold = $lineData['isBold'];

            // Check if the line is a question (e.g., starts with a number and a dot)
            if (preg_match('/^\s*\d+\.\s*(.+)$/u', $lineText, $matches)) {

                // Create a new question
                $currentQuestion = new Question();
                $currentQuestion->test_id = $test_id;
                $currentQuestion->question = $matches[1];
                $currentQuestion->correct_answer = ''; // Set this later if needed

                $currentQuestion->save();

            } elseif (preg_match('/^\s*[a-zA-Zа-яА-ЯёЁ]\s*[.)]\s*(.+)$/u', $lineText, $matches)) {
                // This is an answer
                if ($currentQuestion !== null) {
                    $answerText = $matches[1];
                    $answer = new Answer();
                    $answer->question_id = $currentQuestion->id; // Must save the question first
                    $answer->answer = $answerText;
                    $answer->save();

                    // Check if the first symbol of the answer is bold
                    if ($isBold) {
                        $currentQuestion->correct_answer = $answer->id;
                        $currentQuestion->save(false);
                    }
                }
            }
        }
    }

    public function actionReady($id): \yii\web\Response
    {
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
        $imgPath = Yii::getAlias("@webroot/certificates/template/certificate{$place}.jpeg");
        $image = imagecreatefromjpeg($imgPath);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        $textColor2 = imagecolorallocate($image, 43, 56, 98);
        $fontPath = '/app/frontend/fonts/times.ttf';
        imagettftext($image, 32, 0, 900, 775, $textColor2, $fontPath, $teacher->subject->subject);
        imagettftext($image, 32, 0, 875, 975, $textColor, $fontPath, $teacher->name);
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

    public function actionAddFormula($id, $type){

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

                    if ($model->validate()) {
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
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('add-formula', [
            'model' => $model
        ]);
    }

    public function actionDeleteFormula($id, $test_id){

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
        $files = File::find()->andWhere(['test_id' => $id])->all();
        $questions = Question::find()->where(['test_id' => $id])->all();
        $payments = Payment::find()->andWhere(['test_id' => $id])->all();
        $results = Result::find()->andWhere(['test_id' => $id])->all();
        $startTimes = StartTime::find()->andWhere(['test_id' => $id])->all();

        foreach ($files as $file) {
            if(unlink($file->path)){
                $file->delete();
            }
        }

        foreach ($questions as $question) {
            // Fetch associated answers
            $answers = Answer::find()->andWhere(['question_id' => $question->id])->all();

            // Delete each associated answer
            foreach ($answers as $a) {
                $a->delete();
            }

            // Attempt to delete the associated file if it exists
            $question->delete();
        }

        // Loop through each payment to delete associated files and records
        foreach ($payments as $payment) {
            // Check if the file exists before attempting to delete
            if (file_exists($payment->payment)) {
                // Attempt to delete the file
                if (unlink($payment->payment)) {
                    // File deleted successfully, now delete the record
                    $payment->delete();
                }
            }
        }

        foreach ($results as $result) {
            $result->delete();
        }

        foreach ($startTimes as $startTime) {
            $startTime->delete();
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
