<?php

namespace frontend\controllers;

use frontend\models\Conductor;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use frontend\models\Clientes;
/**
 * ConductorController implements the CRUD actions for Conductor model.
 */
class ConductorController extends Controller
{
     public function actionConductores() {
    $admin = Yii::$app->session->get('usuario_admin');
    $cuit = Yii::$app->session->get('cliente_cuit');
    $conductor = new Conductor;
    
    if ($admin != 1) {
        $conductorModel = new Conductor();
        $conductores = $conductor->find()->where(['cliente_cuit' => $cuit])->all(); 
        return $this->render("conductores-usuario", ['conductores' => $conductores, 'conductor' => $conductorModel,]);
    } else {
        $conductores = $conductor->find()->orderBy(['cliente_cuit' => SORT_ASC])->all(); 
        $conductorModel = new Conductor();
        $clientes = Clientes::find()
            ->select(['cliente_razon_social'])
            ->indexBy('cliente_cuit')
            ->column();
        
        return $this->render("conductores-admin", [
            'conductores' => $conductores,
            'conductor' => $conductor,
            'clientes' => $clientes,
        ]); 
    }
}

public function actionCrearConductor()
{
    $admin = Yii::$app->session->get('usuario_admin');    
    $conductor = new Conductor();  

    if ($admin != 1) {
        if ($conductor->load(Yii::$app->request->post())) {
            $conductor->cliente_cuit = Yii::$app->session->get('cliente_cuit');
            if ($conductor->save()) {
                Yii::$app->session->setFlash('success', '¡Conductor creado correctamente!');
                return $this->redirect(['conductores']);
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el conductor.');
            }
        }

        $conductores = Conductor::find()->where(['cliente_cuit' => Yii::$app->session->get('cliente_cuit')])->all();

        return $this->render('conductores-usuario', [
            'conductores' => $conductores,
            'conductor' => $conductor,
        ]);
    } else {
        $clientes = Clientes::find()
            ->select(['cliente_razon_social'])
            ->indexBy('cliente_cuit')
            ->column();

        if ($conductor->load(Yii::$app->request->post()) && $conductor->save()) {
            Yii::$app->session->setFlash('success', '¡Conductor creado correctamente!');
            return $this->redirect(['conductores']);
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al crear el conductor.');
        }

        $conductores = Conductor::find()->all();

        return $this->render('conductores-admin', [
            'conductores' => $conductores,
            'conductor' => $conductor,
            'clientes' => $clientes,
        ]);
    }
}
   
    
    
  public function actionModificarConductor($dni)
{
    $admin = Yii::$app->session->get('usuario_admin');
    $cuit = Yii::$app->session->get('cliente_cuit');
    $conductor = $this->findConductor($dni);

    if (!$conductor) {
        throw new NotFoundHttpException('El conductor no existe.');
    }

    if ($admin == 1) {
        // Para administrador
        $clientes = Clientes::find()
            ->select(['cliente_razon_social'])
            ->indexBy('cliente_cuit')
            ->column();

        if ($conductor->load(Yii::$app->request->post()) && $conductor->save()) {
            Yii::$app->session->setFlash('success', 'Conductor modificado correctamente.');
            return $this->redirect(['conductores']);
        }
        Yii::$app->session->setFlash('error', 'Hubo un error al modificar el conductor.');
        return $this->render('conductores-admin', ['conductores' => Conductor::find()->all(), 'conductor' => $conductor, 'clientes' => $clientes]);
    } else {
        // Para usuario no administrador
        if ($conductor->load(Yii::$app->request->post()) && $conductor->save()) {
            $conductor->cliente_cuit = $cuit;
            $conductor->save();
            Yii::$app->session->setFlash('success', 'Conductor modificado correctamente.');
            return $this->redirect(['conductores']);
        }
        Yii::$app->session->setFlash('error', 'Hubo un error al modificar el conductor.');
        return $this->render('conductores-usuario', ['conductores' => Conductor::find()->all(), 'conductor' => $conductor]);
    }
}
    public function actionEliminarConductor($dni)
        {
        $this->findConductor($dni)->delete();
        Yii::$app->session->setFlash('success', 'Conductor eliminado correctamente.');
        return $this->redirect(['conductores']);
        }
   
    protected function findConductor($dni)
        {
        if (($conductor = Conductor::findOne($dni)) !== null) {
            return $conductor;
        }
        throw new NotFoundHttpException('El conductor no existe.');
        }
   
   
 
    
        public function beforeAction($action)
    {
        // Verificar si el usuario está autenticado
        if (!Yii::$app->user->isGuest) {
            // Obtener el nombre de usuario del cliente autenticado
            $usuarioId = Yii::$app->user->identity->usuario_id;
            $admin = Yii::$app->user->identity->usuario_admin;
            $cuit = Clientes::find()->select('cliente_cuit')->where(['usuario_id' => $usuarioId])->scalar();

            // Asignar el cuit a la variable de sesión
            Yii::$app->session->set('usuario_admin', $admin);
            Yii::$app->session->set('cliente_cuit', $cuit);
            Yii::$app->session->set('usuario_id', $usuarioId);
        }

        return parent::beforeAction($action);
    }
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
    
    
     
}
