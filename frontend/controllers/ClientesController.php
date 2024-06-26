<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Clientes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\modules\user\models\User;

/**
 * ClientesController implements the CRUD actions for Clientes model.
 */
class ClientesController extends Controller
{
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

    // Acción para mostrar los clientes
    public function actionClientes()
    {
        $admin = Yii::$app->session->get('usuario_admin');
        $tabla = new Clientes;

        if ($admin == 1) {
            $clientes = $tabla->find()->all();
            return $this->render('clientes-admin', ['clientes' => $clientes]);
        } else {
            $usuarioId = Yii::$app->session->get('usuario_id');
            $clientes = $tabla->find()->where(['usuario_id' => $usuarioId])->all();
            return $this->render('clientes-usuario', ['clientes' => $clientes]);
        }
    }

    // Acción para eliminar un cliente
    public function actionEliminarCliente($cuit)
    {
        $cliente = $this->findClientes($cuit);
        if ($cliente->delete()) {
            Yii::$app->session->setFlash('success', 'Cliente eliminado correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al eliminar el cliente.');
        }

        return $this->redirect(['clientes']);
    }

    // Acción para crear un cliente
    public function actionCrearCliente()
    {
        $cliente = new Clientes();
        $admin = Yii::$app->session->get('usuario_admin');

        // Verificar si el usuario es un administrador
        if ($admin == 1) {
            $request = Yii::$app->request;
            // Si el formulario se envía y los datos se cargan correctamente en el modelo, intentamos guardar el cliente
            if ($request->isPost && $cliente->load($request->post())) {
                if ($cliente->save()) {
                    Yii::$app->session->setFlash('success', 'Cliente creado correctamente.');
                    return $this->redirect(['clientes']);
                } else {
                    Yii::$app->session->setFlash('error', 'Hubo un error al guardar el cliente.');
                }
            }
            // Mostrar el formulario de creación de cliente
            return $this->render('crear-cliente', ['cliente' => $cliente]);
        }
    }

    // Acción para modificar un cliente (administrador)
    public function actionModificarCliente($cuit)
    {
        $cliente = $this->findClientes($cuit);
        if ($cliente->load(Yii::$app->request->post()) && $cliente->save()) {
            Yii::$app->session->setFlash('success', 'Cliente modificado correctamente.');
            return $this->redirect(['clientes']);
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al modificar el cliente.');
        }
        return $this->render('modificar-cliente', ['cliente' => $cliente]);
    }

    // Acción para modificar un cliente (usuario)
    public function actionModificar()
    {
        $cuit = Yii::$app->session->get('cuit');
        $cliente = $this->findClientes($cuit);
        $user = $cliente->user;

        if ($cliente->load(Yii::$app->request->post()) && $cliente->save()) {
            Yii::$app->session->set('cuit', $cliente->cliente_cuit);

            $user->nombre = $cliente->cliente_razon_social;
            $user->email = $cliente->cliente_mail;
            $user->cuit = $cliente->cliente_cuit;
            $user->save();

            Yii::$app->session->setFlash('success', 'Cliente modificado correctamente.');
            return $this->redirect(['datos']);
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al modificar el cliente.');
        }
        return $this->render('modificar', ['cliente' => $cliente]);
    }

    // Método protegido para encontrar un cliente según el CUIT
    protected function findClientes($cuit)
    {
        $cliente = Clientes::findOne(['cliente_cuit' => $cuit]);
        if ($cliente === null) {
            throw new NotFoundHttpException('El cliente no existe.');
        }
        return $cliente;
    }
}