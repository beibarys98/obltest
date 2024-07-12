<?php

namespace frontend\controllers;

use common\models\Formula;
use common\models\Question;
use common\models\Test;
use common\models\TestSearch;
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
            'query' => Test::find()->andWhere(['status' => 'загрузите формулы']),
        ]);

        $dataProvider2 = new ActiveDataProvider([
            'query' => Test::find()->andWhere(['status' => 'готов к публикаций'])
        ]);

        $dataProvider3 = new ActiveDataProvider([
            'query' => Test::find()->andWhere(['status' => 'опубликован'])
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'dataProvider3' => $dataProvider3,
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

                if($model->has_equation){
                    $model->status = 'загрузите формулы';
                }else{
                    $model->status = 'готов к публикаций';
                }

                $model->save();

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

    public function actionPublish($id){
        $test = Test::findOne($id);
        $test->status = 'опубликован';
        $test->save(false);

        return $this->redirect(['index']);
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

                if($model->has_equation){
                    $model->status = 'загрузите формулы';
                }else{
                    $model->status = 'готов к публикаций';
                }

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
