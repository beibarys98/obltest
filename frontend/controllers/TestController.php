<?php

namespace frontend\controllers;

use common\models\Question;
use common\models\Test;
use common\models\TestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TestController implements the CRUD actions for Test model.
 */
class TestController extends Controller
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
     * Lists all Test models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TestSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Test model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model2 = Question::find()->andWhere(['test_id' => $id])->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'model2' => $model2,
        ]);
    }

    /**
     * Creates a new Test model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Test();
        $questions = [];

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                if($model->has_equation){
                    $model->status = 'загрузите формулы';
                }else{
                    $model->status = 'ожидает начала';
                }

                $lines = explode("\n", $model->test);

                $number = 1;
                foreach ($lines as $line) {
                    $data = explode("\t", $line); // Split each line by tabs to get question and answers

                    if (count($data) == 6) { // Ensure there are at least 5 elements (question, 4 answers, correct answer)
                        $questionModel = new Question();
                        $questionModel->number = $number;
                        $questionModel->question = trim($data[0]);
                        $questionModel->answer1 = trim($data[1]);
                        $questionModel->answer2 = trim($data[2]);
                        $questionModel->answer3 = trim($data[3]);
                        $questionModel->answer4 = trim($data[4]);
                        $questionModel->correct_answer = trim($data[5]);

                        $questions[] = $questionModel;

                        $number++;
                    }
                }

                if ($model->save()) {
                    // If $model->save() succeeds, you can save each Question model in $questions array
                    foreach ($questions as $question) {
                        $question->test_id = $model->id; // Assuming test_id is the foreign key linking Test and Question models
                        $question->save(); // Save each Question model
                    }

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Test model.
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
     * Deletes an existing Test model.
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
     * Finds the Test model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Test the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Test::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
