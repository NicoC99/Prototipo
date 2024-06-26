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
     public function actionConductores(){
        $admin= Yii::$app->session->get('usuario_admin');
        $cuit= Yii::$app->session->get('cliente_cuit');
        $conductor = new Conductor;
        
        
        if ($admin != 1){
           $model = $conductor->find()->where(['cliente_cuit' => $cuit])->all(); 
        return $this->render("conductores-usuario", ["model" => $model]);
        } else {
           $model = $conductor->find()->orderBy(['cliente_cuit' => SORT_ASC])->all(); 
           return $this->render("conductores-admin", ["model" => $model]); 
        }
        }
        
        public function actionCrearConductor()
        {
        $admin= Yii::$app->session->get('usuario_admin');    
        $conductor = new Conductor();  

            if ($admin != 1){
            if ($conductor->load(Yii::$app->request->post())) {
                $conductor->cliente_cuit = Yii::$app->session->get('cuit'); // Asignar el CUIT del usuario actual al campo "cliente"
                if ($conductor->save()) {
                    // El modelo se guardó correctamente, redirige a la lista de conductores
                    return $this->redirect(['conductores']);
                }
            }

            return $this->render('crear-conductor-usuario', ['conductor' => $conductor]);
            }else{
                 $clientes = Clientes::find()
                ->select(['cliente_razon_social'])
                ->indexBy('cliente_cuit')
                ->column();
                if ($conductor->load(Yii::$app->request->post())) {
                if ($conductor->save()) {
                    // El modelo se guardó correctamente, redirige a la lista de conductores
                    return $this->redirect(['conductores']);
                }
            }
            }
            return $this->render('crear-conductor-admin', ['conductor' => $conductor,'clientes' =>$clientes]);

        }
   
    
    
   public function actionModificarConductor($dni)
{
    $admin = Yii::$app->session->get('usuario_admin');
    $cuit = Yii::$app->session->get('cliente_cuit');
    $conductor = $this->findConductor($dni);

    if ($admin == 1) {
        // Para administrador
        $clientes = Clientes::find()
            ->select(['cliente_razon_social'])
            ->indexBy('cliente_cuit')
            ->column();

        if ($conductor->load(Yii::$app->request->post()) && $conductor->save()) {
            return $this->redirect(['conductores']);
        }
        return $this->render('modificar-conductor-admin', ['conductor' => $conductor, 'clientes' => $clientes]);
    } else {
        // Para usuario no administrador
        if ($conductor->load(Yii::$app->request->post()) && $conductor->save()) {
            $conductor->cliente_cuit = $cuit;
            $conductor->save();
            return $this->redirect(['conductores']);
        }
        return $this->render('modificar-conductor-usuario', ['conductor' => $conductor]);
    }
}

    public function actionEliminarConductor($dni)
        {
        $this->findConductor($dni)->delete();
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
