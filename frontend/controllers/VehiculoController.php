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
    $vehiculo = new Vehiculo;

    if ($admin != 1) {
        $vehiculos = $vehiculo->find()->where(['cliente_cuit' => $cuit])->all();
        return $this->render("vehiculos-usuario", ["vehiculos" => $vehiculos, "vehiculo" => $vehiculo]);
    } else {
        $vehiculos = $vehiculo->find()->orderBy(['cliente_cuit' => SORT_ASC])->all();
        $clientes = Clientes::find()
            ->select(['cliente_razon_social'])
            ->indexBy('cliente_cuit')
            ->column();
        return $this->render("vehiculos-admin", [
            "vehiculos" => $vehiculos,
            "vehiculo" => $vehiculo,
            "clientes" => $clientes,
        ]);
    }
}
    // Acción para editar un vehículo
    public function actionModificarVehiculo($vehiculoPatente)
    {
        $admin = Yii::$app->session->get('usuario_admin');
        $cuit = Yii::$app->session->get('cliente_cuit');
        $vehiculo = $this->findVehiculo($vehiculoPatente);

        if ($admin == 1) {
            // Para administrador
            $clientes = Clientes::find()
                ->select(['cliente_razon_social'])
                ->indexBy('cliente_cuit')
                ->column();

            if ($vehiculo->load(Yii::$app->request->post()) && $vehiculo->save()) {
                Yii::$app->session->setFlash('success', 'Vehículo modificado correctamente.');
                return $this->redirect(['vehiculos']);
            }
            Yii::$app->session->setFlash('error', 'Hubo un error al modificar el vehículo.');
            return $this->render('vehiculos-admin', ['vehiculos' => Vehiculo::find()->all(),'vehiculo' => $vehiculo, 'clientes' => $clientes]);
        } else {
            // Para usuario no administrador
            if ($vehiculo->load(Yii::$app->request->post()) && $vehiculo->save()) {
                $vehiculo->cliente_cuit = $cuit;
                $vehiculo->save();
                Yii::$app->session->setFlash('success', 'Vehículo modificado correctamente.');
                return $this->redirect(['vehiculos']);
            }
            Yii::$app->session->setFlash('error', 'Hubo un error al modificar el vehículo.');
            return $this->render('vehiculos-usuario', ['vehiculos' => Vehiculo::find()->all(),'vehiculo' => $vehiculo]);
        }
    }
    
    public function actionEliminarVehiculo($vehiculoPatente)
    {
        $this->findVehiculo($vehiculoPatente)->delete();
        Yii::$app->session->setFlash('success', 'Vehículo eliminado correctamente.');
        return $this->redirect(['vehiculos']);
    }

    public function actionCrearVehiculo()
    {
        $vehiculo = new Vehiculo();
        $admin = Yii::$app->session->get('usuario_admin');

        if ($admin != 1) {
            if ($vehiculo->load(Yii::$app->request->post())) {
                $vehiculo->cliente_cuit = Yii::$app->session->get('cliente_cuit'); // Asignar el CUIT del usuario actual al campo "cliente"
                if ($vehiculo->save()) {
                    Yii::$app->session->setFlash('success', '¡Vehículo creado correctamente!');
                    return $this->redirect(['vehiculos']);
                } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el vehículo.');
            }
            }
             $vehiculos = Vehiculo::find()->where(['cliente_cuit' => Yii::$app->session->get('cliente_cuit')])->all();
            return $this->render('vehiculos-usuario', ['vehiculo' => $vehiculo, 'vehiculos' => $vehiculos]);
        } else {
            $clientes = Clientes::find()
                ->select(['cliente_razon_social'])
                ->indexBy('cliente_cuit')
                ->column();

            if ($vehiculo->load(Yii::$app->request->post()) && $vehiculo->save()) {
                Yii::$app->session->setFlash('success', '¡Vehículo creado correctamente!');
                return $this->redirect(['vehiculos']);
            } else{
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el vehículo.');
            }
             $vehiculos = Vehiculo::find()->all();
            return $this->render('vehiculos-admin', ['vehiculo' => $vehiculo, 'clientes' => $clientes, 'vehiculos' => $vehiculos]);
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