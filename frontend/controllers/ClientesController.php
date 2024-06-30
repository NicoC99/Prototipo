<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Clientes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ClientesController extends Controller
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

    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            $usuarioId = Yii::$app->user->identity->usuario_id;
            $admin = Yii::$app->user->identity->usuario_admin;
            $cuit = Clientes::find()->select('cliente_cuit')->where(['usuario_id' => $usuarioId])->scalar();

            Yii::$app->session->set('usuario_admin', $admin);
            Yii::$app->session->set('cliente_cuit', $cuit);
            Yii::$app->session->set('usuario_id', $usuarioId);
        }

        return parent::beforeAction($action);
    }

    public function actionClientes()
    {
        $admin = Yii::$app->session->get('usuario_admin');
        $clienteModel = new Clientes;

        if ($admin == 1) {
            $clientes = $clienteModel->find()->all();
            return $this->render('clientes-admin', ['clientes' => $clientes, 'cliente' => $clienteModel]);
        } else {
            $usuarioId = Yii::$app->session->get('usuario_id');
            $clientes = $clienteModel->find()->where(['usuario_id' => $usuarioId])->all();
            return $this->render('clientes-usuario', ['clientes' => $clientes, 'cliente' => $clienteModel]);
        }
    }

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

    public function actionCrearCliente()
    {
        $clienteModel = new Clientes();
        $admin = Yii::$app->session->get('usuario_admin');

        if ($admin == 1) {
            $request = Yii::$app->request;
            if ($request->isPost && $clienteModel->load($request->post()) && $clienteModel->save()) {
                Yii::$app->session->setFlash('success', 'Cliente creado correctamente.');
                return $this->redirect(['clientes']);
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al guardar el cliente.');
            }
            return $this->render('clientes-admin', ['cliente' => $clienteModel, 'clientes' => $clienteModel->find()->all()]);
        }

        return $this->redirect(['clientes']);
    }

    public function actionModificarCliente($cuit)
    {
        $admin = Yii::$app->session->get('usuario_admin');
        $clienteModel = $this->findClientes($cuit);

        if ($clienteModel->load(Yii::$app->request->post()) && $clienteModel->save()) {
            Yii::$app->session->setFlash('success', 'Cliente modificado correctamente.');
            return $this->redirect(['clientes']);
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al modificar el cliente.');
        }

        if ($admin == 1) {
            return $this->render('clientes-admin', ['cliente' => $clienteModel, 'clientes' => Clientes::find()->all()]);
        } else {
            return $this->render('clientes-usuario', ['cliente' => $clienteModel, 'clientes' => Clientes::find()->where(['usuario_id' => Yii::$app->session->get('usuario_id')])->all()]);
        }
    }

    protected function findClientes($cuit)
    {
        if (($cliente = Clientes::findOne(['cliente_cuit' => $cuit])) !== null) {
            return $cliente;
        }
        throw new NotFoundHttpException('El cliente no existe.');
    }
}