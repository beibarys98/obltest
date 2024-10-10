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
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['login']);
        }

        if(Admin::findOne(Yii::$app->user->identity->id)){
            return $this->redirect(['/test/index']);
        }

        //find the teacher
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);

        //calculate the next version
        $availableVersions = Test::find()
            ->andWhere(['subject_id' => $teacher->subject_id])
            ->andWhere(['language' => $teacher->language])
            ->andWhere(['status' => 'public'])
            ->select(['version'])
            ->distinct()
            ->orderBy('version')
            ->asArray()
            ->all();
        if(!$availableVersions){
            return $this->render('index-null');
        }
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

        //find test taker
        $testTaker = TestTaker::findOne(['teacher_id' => $teacher->id]);
        if($testTaker){
            $test = Test::find()
                ->andWhere(['subject_id' => $teacher->subject_id])
                ->andWhere(['language' => $teacher->language])
                ->andWhere(['version' => $testTaker->test->version])
                ->andWhere(['status' => ['public', 'finished', 'certificated']])
                ->one();
        } else {
            $test = Test::find()
                ->andWhere(['subject_id' => $teacher->subject_id])
                ->andWhere(['language' => $teacher->language])
                ->andWhere(['version' => $nextVersion])
                ->andWhere(['status' => ['public', 'finished', 'certificated']])
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
        $test = Test::findOne($id);
        $questions = Question::find()->andWhere(['test_id' => $test->id])->all();
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);

        $unansweredQuestion = Question::find()
            ->leftJoin('teacher_answer', 'question.id = teacher_answer.question_id')
            ->andWhere(['question.test_id' => $id])
            ->andWhere(['teacher_answer.id' => null])
            ->one();

        if ($unansweredQuestion) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Ответьте на все вопросы!'));
            return $this->redirect(['site/view', 'id' => $unansweredQuestion->id]);
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

//        //save results in pdf
//        $content = $this->renderPartial('report', [
//            'test' => $test,
//            'questions' => $questions,
//            'answers' => $postData
//        ]);
//
//        $pdf = new Pdf([
//            'mode' => Pdf::MODE_UTF8,
//            'content' => $content,
//            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
//            'cssInline' => '.kv-heading-1{font-size:18px}'
//        ]);
//
//        //save pdf in db
//        $pdfOutput = $pdf->render();
//        $pdfFilePath = Yii::getAlias('@webroot/reports/')
//            . Yii::$app->security->generateRandomString(8)
//            . '.pdf';
//        file_put_contents($pdfFilePath, $pdfOutput);
//
//        $report = new File();
//        $report->teacher_id = $teacher->id;
//        $report->test_id = $test->id;
//        $report->path = $pdfFilePath;
//        $report->save(false);

        return $this->redirect(['/site/index']);
    }

    public function actionDownload($id)
    {
        $file = File::findOne($id);
        if (preg_match('/\.pdf$/i', $file->path)) {
            $text = 'Қатемен жұмыс.pdf';
        } elseif (preg_match('/\.(jpeg|jpg)$/i', $file->path)) {
            $text = 'Сертификат.jpeg';
        } else {
            $text = $file->path;
        }
        return Yii::$app->response->sendFile($file->path, $text);
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

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
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

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
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
