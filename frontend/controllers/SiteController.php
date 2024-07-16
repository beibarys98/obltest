<?php

namespace frontend\controllers;

use Imagine\Gd\Imagine;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use common\models\Admin;
use common\models\Question;
use common\models\Teacher;
use common\models\Test;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use kartik\mpdf\Pdf;
use Mpdf\Mpdf;
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
use yii\web\NotFoundHttpException;

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

        if(Admin::findOne(Yii::$app->user->id)){
            return $this->redirect(['test/index']);
        }

        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);

        $test = new ActiveDataProvider([
            'query' => Test::find()->andWhere(['subject_id' => $teacher->subject_id]),
        ]);

        return $this->render('index', [
            'test' => $test,
        ]);
    }

    public function actionView($id, $postData = '')
    {
        $questions = Question::find()->andWhere(['test_id' => $id])->all();

        return $this->render('view', [
            'test' => Test::findOne($id),
            'questions' => $questions,
            'answers' => $postData,
        ]);
    }

    public function actionSubmit()
    {
        if (Yii::$app->request->isPost) {
            $test = Test::findOne(Yii::$app->request->post('test_id'));
            $questions = Question::find()->andWhere(['test_id' => $test->id])->all();
            $postData = Yii::$app->request->post('answers', []);
            $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);

            foreach ($questions as $q) {
                if (!isset($postData[$q->id])) {
                    $postData[$q->id] = '';
                }
            }

            $content = $this->renderPartial('result', [
                'test' => $test,
                'questions' => $questions,
                'answers' => $postData
            ]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'content' => $content,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}'
            ]);

            $pdfOutput = $pdf->render();
            $pdfFilePath = Yii::getAlias('@webroot/reports/')
                . $teacher->id
                . $test->id
                . '.pdf';
            file_put_contents($pdfFilePath, $pdfOutput);

            $imgSrc = Yii::getAlias('@webroot/certificates/certificate.jpg');
            $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);
            $content2 = $this->renderPartial('image', [
                'imgSrc' => $imgSrc,
                'teacher' => $teacher,
            ]);

            $pdf2 = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'content' => $content2,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'orientation' => 'L'
            ]);

            $pdfOutput2 = $pdf2->render();
            $pdfFilePath2 = Yii::getAlias('@webroot/certificates/')
                . $teacher->id
                . $test->id
                . '.pdf';
            file_put_contents($pdfFilePath2, $pdfOutput2);

            return $this->redirect(['end', 'id' => $test->id]);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionEnd($id){
        $test = Test::findOne($id);
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);

        $pdfUrl = Yii::$app->request->baseUrl
            . '/reports/'
            . $teacher->id
            . $test->id
            . '.pdf';

        $imgSrc = Yii::$app->request->baseUrl . '/certificates/certificate.jpg';

        return $this->render('end', [
            'test' => Test::findOne($id),
            'pdfUrl' => $pdfUrl,
            'imgSrc' => $imgSrc
        ]);
    }

    public function actionCertificate()
    {
        $filePath = Yii::getAlias('@webroot/certificates/certificate.jpg');
        return Yii::$app->response->sendFile($filePath, 'certificate.jpg');
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
}
