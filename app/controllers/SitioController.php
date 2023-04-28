<?php

namespace app\controllers;

use Yii;
use yii\filters;
use yii\web\Controller;
use app\models\FormularioForm;

class SitioController extends Controller
{
    public function actionInicio()
    {
        $model = new FormularioForm();
        $valorRespuesta = '';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $valorRespuesta = ("El resultado es: " . $model->valora + $model->valorb);
        }


        return $this->render('inicio', ['model'=>$model, 'respuesta'=>$valorRespuesta]);
    }
}