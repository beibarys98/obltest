<?php

namespace frontend\controllers;

use common\models\File;
use common\models\Payment;
use common\models\Result;
use common\models\Admin;
use common\models\Question;
use common\models\Teacher;
use common\models\TeacherAnswer;
use common\models\Test;
use common\models\TestTaker;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\SignupForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }

        if(Admin::findOne(Yii::$app->user->identity->id)){
            return $this->redirect(['/test/index']);
        }

        //find the teacher
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);

        //not a newbie? show the test
        $testTaker = TestTaker::findOne(['teacher_id' => $teacher->id]);
        if($testTaker){
            $test = Test::findOne($testTaker->test_id);
        }

        //newbie? the next version
        else {
            //how many versions of the test
            $availableVersions = Test::find()
                ->andWhere(['subject_id' => $teacher->subject_id])
                ->andWhere(['language' => $teacher->language])
                ->andWhere(['status' => 'public'])
                ->select(['version'])
                ->distinct()
                ->orderBy('version')
                ->asArray()
                ->all();

            //no versions? show null
            if(!$availableVersions){
                return $this->render('index-null');
            }

            //there are? give the newbie the next version
            $versions = array_column($availableVersions, 'version');
            $testIds = Test::find()
                ->select('id')
                ->andWhere(['subject_id' => $teacher->subject_id])
                ->andWhere(['language' => $teacher->language])
                ->andWhere(['status' => 'public'])
                ->column();
            $testTakerCount = TestTaker::find()
                ->andWhere(['test_id' => $testIds])
                ->count();
            $nextVersionIndex = $testTakerCount % count($versions);
            $nextVersion = $versions[$nextVersionIndex];

            $test = Test::find()
                ->andWhere(['subject_id' => $teacher->subject_id])
                ->andWhere(['language' => $teacher->language])
                ->andWhere(['version' => $nextVersion])
                ->one();

            $testTaker = new TestTaker();
            $testTaker->teacher_id = $teacher->id;
            $testTaker->test_id = $test->id;
            $testTaker->save(false);
        }

        //find the certificate
        $certificate = new ActiveDataProvider([
            'query' => File::find()
                ->andWhere(['teacher_id' => $teacher->id])
                ->andWhere(['test_id' => $test->id])
                ->andWhere(['LIKE', 'path', '%\.jpeg', false])
        ]);

        //is test active? and was it paid?
        $now = new \DateTime();
        $startTime = new \DateTime($test->start_time);
        $endTime = new \DateTime($test->end_time);
        $isActive = $now >= $startTime
            && $now <= $endTime
            && $test->status == 'public'
            && !$testTaker->end_time;

        $hasPaid = Payment::find()
            ->andWhere(['teacher_id' => $teacher->id])
            ->andWhere(['test_id' => $test->id])
            ->one();

        return $this->render('index', [
            'test' => $test,
            'certificate' => $certificate,
            'isActive' => $isActive,
            'hasPaid' => $hasPaid
        ]);
    }

    public function actionView($id)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }

        $question = Question::findOne([$id]);
        $test = Test::findOne($question->test_id);

        $testTaker = TestTaker::find()
            ->andWhere(['teacher_id' => Teacher::findOne(['user_id' => Yii::$app->user->id])->id])
            ->andWhere(['test_id' => $test->id])
            ->one();
        if(!$testTaker->start_time){
            $testTaker->start_time = (new \DateTime())->format('Y-m-d H:i:s');
            $testTaker->save(false);
        }

        return $this->render('/site/view', [
            'test' => $test,
            'question' => $question,
            'testTaker' => $testTaker,
        ]);
    }

    public function actionSubmit()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }

        $answerId = Yii::$app->request->get('answer_id');
        $questionId = Yii::$app->request->get('question_id');

        $teacherId = Teacher::findOne(['user_id' => Yii::$app->user->id])->id;
        $teacherAnswer = TeacherAnswer::findOne([
            'teacher_id' => $teacherId,
            'question_id' => $questionId,
        ]);

        if (!$teacherAnswer) {
            $teacherAnswer = new TeacherAnswer();
            $teacherAnswer->teacher_id = $teacherId;
            $teacherAnswer->question_id = $questionId;
        }
        $teacherAnswer->answer_id = $answerId;
        $teacherAnswer->save(false);

        $nextQuestion = Question::find()
            ->andWhere(['test_id' => Question::findOne($questionId)->test_id])
            ->andWhere(['>', 'id', $questionId])
            ->orderBy(['id' => SORT_ASC])
            ->one();

        if (!$nextQuestion) {
            $nextQuestion = Question::find()
                ->andWhere(['test_id' => Question::findOne($questionId)->test_id])
                ->orderBy(['id' => SORT_ASC])
                ->one();
        }

        return $this->redirect(['site/view', 'id' => $nextQuestion->id]);
    }

    public function actionEnd($id){
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }

        $test = Test::findOne($id);
        $questions = Question::find()->andWhere(['test_id' => $test->id])->all();
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);
        $testTaker = TestTaker::findOne(['teacher_id' => $teacher->id]);

        //unanswered questions? return to test
        $now = new \DateTime();
        $startTime = new \DateTime($testTaker->start_time);
        $testDuration = new \DateTime($test->duration);
        $durationInSeconds =
            ($testDuration->format('H') * 3600)
            + ($testDuration->format('i') * 60)
            + $testDuration->format('s');
        $timeElapsed = $now->getTimestamp() - $startTime->getTimestamp();

        if ($timeElapsed < $durationInSeconds) {
            $unansweredQuestion = Question::find()
                ->leftJoin('teacher_answer', 'question.id = teacher_answer.question_id AND teacher_answer.teacher_id = :teacher_id', [':teacher_id' => $teacher->id])
                ->andWhere(['question.test_id' => $id])
                ->andWhere(['teacher_answer.answer_id' => null])
                ->one();

            if ($unansweredQuestion) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Ответьте на все вопросы!'));
                return $this->redirect(['site/view', 'id' => $unansweredQuestion->id]);
            }
        }

        //save end time
        $testTaker = TestTaker::find()
            ->andWhere(['teacher_id' => Teacher::findOne(['user_id' => Yii::$app->user->id])->id])
            ->andWhere(['test_id' => $test->id])
            ->one();
        $testTaker->end_time = (new \DateTime())->format('Y-m-d H:i:s');
        $testTaker->save(false);

        //save results in db
        $score = 0;
        foreach ($questions as $q) {
            $teacherAnswerModel = TeacherAnswer::findOne(['teacher_id' => $teacher->id, 'question_id' => $q->id]);
            if ($teacherAnswerModel !== null) {
                $teacherAnswer = $teacherAnswerModel->answer_id;
                if ($teacherAnswer == $q->correct_answer) {
                    $score++;
                }
            }
        }
        $result = new Result();
        $result->teacher_id = $teacher->id;
        $result->test_id = $test->id;
        $result->result = $score;
        $result->save(false);

        //save report in pdf
        $answers = TeacherAnswer::find()
            ->andWhere(['teacher_id' => $teacher->id])
            ->indexBy('question_id')
            ->all();
        $testDP = new ActiveDataProvider([
            'query' => Test::find()
                ->andWhere(['id' => $test->id]),
        ]);
        $resultDP = new ActiveDataProvider([
            'query' => Result::find()
                ->andWhere(['teacher_id' => $teacher->id])
                ->andWhere(['test_id' => $test->id]),
        ]);
        $content2 = $this->renderPartial('report', [
            'test' => $test,
            'questions' => $questions,
            'answers' => $answers,
            'testDP' => $testDP,
            'resultDP' => $resultDP,
        ]);
        $pdf2 = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'content' => $content2,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'filename' => 'Қатемен жұмыс.pdf',
        ]);
        $pdf2Output = $pdf2->render();
        $pdfFilePath2 = Yii::getAlias('@webroot/reports/')
            . Yii::$app->security->generateRandomString(8)
            . '.pdf';
        file_put_contents($pdfFilePath2, $pdf2Output);
        $report = new File();
        $report->teacher_id = $teacher->id;
        $report->test_id = $id;
        $report->path = $pdfFilePath2;
        $report->save(false);

        return $this->redirect(['/site/index']);
    }

    public function actionDownload($id)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }

        $file = File::findOne($id);

        return Yii::$app->response->sendFile($file->path, 'Сертификат.jpeg');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        $model2 = new Teacher();
        if ($model->load(Yii::$app->request->post())
            && $model2->load(Yii::$app->request->post())
            && $model->signup($model2)) {

            Yii::$app->session->setFlash('success', Yii::t('app', 'Регистрация прошла успешно!'));
            $model2->save(false);
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
            'model2' => $model2,
        ]);
    }

    public function actionLanguage($view)
    {
        if(Yii::$app->language == 'kz-KZ'){
            Yii::$app->session->set('language', 'ru-RU');
        }else{
            Yii::$app->session->set('language', 'kz-KZ');
        }
        return $this->redirect([$view]);
    }
}
