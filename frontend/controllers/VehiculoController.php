<?php

namespace frontend\controllers;

use frontend\models\Vehiculo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use frontend\models\Clientes;

class VehiculoController extends Controller
{
    // Acción para mostrar los camiones ingresados
    public function actionVehiculos()
    {
        $admin = Yii::$app->session->get('usuario_admin');
        $usuarioId = Yii::$app->session->get('usuario_id');
        $cuit = Clientes::find()->select(['cliente_cuit'])->where(['usuario_id' => $usuarioId])->scalar();
        $tabla = new Vehiculo;

        if ($admin != 1) {
            $model = $tabla->find()->where(['cliente_cuit' => $cuit])->all();
            return $this->render("vehiculos-usuario", ["model" => $model]);
        } else {
            $model = $tabla->find()->orderBy(['cliente_cuit' => SORT_ASC])->all();
            return $this->render("vehiculos-admin", ["model" => $model]);
        }
    }

    // Acción para editar un vehículo
    public function actionModificarVehiculo($vehiculoPatente)
    {
        $admin = Yii::$app->session->get('usuario_admin');
        $cuit = Yii::$app->session->get('cliente_cuit');
        $vehiculos = $this->findVehiculo($vehiculoPatente);

        if ($admin == 1) {
            // Para administrador
            $clientes = Clientes::find()
                ->select(['cliente_razon_social'])
                ->indexBy('cliente_cuit')
                ->column();

            if ($vehiculos->load(Yii::$app->request->post()) && $vehiculos->save()) {
                return $this->redirect(['vehiculos']);
            }
            return $this->render('modificar-vehiculo-admin', ['vehiculos' => $vehiculos, 'clientes' => $clientes]);
        } else {
            // Para usuario no administrador
            if ($vehiculos->load(Yii::$app->request->post()) && $vehiculos->save()) {
                $vehiculos->cliente_cuit = $cuit;
                $vehiculos->save();
                return $this->redirect(['vehiculos']);
            }
            return $this->render('modificar-vehiculo-usuario', ['vehiculos' => $vehiculos]);
        }
    }

    // Acción para borrar un camión
    public function actionEliminarVehiculo($vehiculoPatente)
    {
        $this->findVehiculo($vehiculoPatente)->delete();
        return $this->redirect(['vehiculos']);
    }

    // Acción para crear un nuevo camión
    public function actionCrearVehiculo()
    {
        $model = new Vehiculo();
        $admin = Yii::$app->session->get('usuario_admin');

        if ($admin != 1) {
            if ($model->load(Yii::$app->request->post())) {
                $model->cliente_cuit = Yii::$app->session->get('cliente_cuit'); // Asignar el CUIT del usuario actual al campo "cliente"
                if ($model->save()) {
                    return $this->redirect(['vehiculos']);
                }
            }
            return $this->render('crear-vehiculo-usuario', ['model' => $model]);
        } else {
            $clientes = Clientes::find()
                ->select(['cliente_razon_social'])
                ->indexBy('cliente_cuit')
                ->column();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['vehiculos']);
            }
            return $this->render('crear-vehiculo-admin', ['model' => $model, 'clientes' => $clientes]);
        }
    }

    // Método protegido para encontrar un modelo de camiones según el ID
    protected function findVehiculo($vehiculoPatente)
    {
        if (($vehiculoPatente = Vehiculo::findOne($vehiculoPatente)) !== null) {
            return $vehiculoPatente;
        }
        throw new NotFoundHttpException('El vehículo no existe.');
    }

    // Método antes de ejecutar cualquier acción
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
    
    // Comportamientos del controlador, incluye el filtro de verbos
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