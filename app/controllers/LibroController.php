<?php

namespace app\controllers;

use app\models\Libro;
use app\models\LibroSearch;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * LibroController implements the CRUD actions for Libro model.
 */
class LibroController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only'=>['index','view','create','update','delete'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@']
                        ]
                    ]
                ],
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
     * Lists all Libro models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LibroSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Libro model.
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
     * Creates a new Libro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Libro();

        $this->subirFoto($model);

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Libro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->subirFoto($model);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Libro model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (file_exists($model->imagen)) {
            unlink($model->imagen);
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Libro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Libro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Libro::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function subirFoto(Libro $model) {
        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
                $model->archivo = UploadedFile::getInstance($model, 'archivo');
                if ($model->validate()) {
                    if ($model->archivo) {

                        if ($model->imagen && file_exists($model->imagen)) {
                            unlink($model->imagen);
                        }

                        $rutaFile = 'uploads/'.time().'_'.$model->archivo->baseName.'.'.$model->archivo->extension;
                        if ($model->archivo->saveAs($rutaFile)) {
                            $model->imagen = $rutaFile;
                        }
                    }
                }

               if($model->save(false)) {
                   return $this->redirect(['index']);
               }

            }
        } else {
            $model->loadDefaultValues();
        }
    }

    public function actionList()
    {
        $model = Libro::find();
        $pagination = new Pagination([
            'defaultPageSize'=>2,
            'totalCount' => $model->count()
        ]);

        $books = $model->orderBy('titulo')->offset($pagination->offset)->limit($pagination->limit)->all();

        return $this->render('lista', ['books' => $books, 'pagination' => $pagination]);
    }
}
