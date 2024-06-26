<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\Turno;
use yii\web\NotFoundHttpException;
use frontend\models\Clientes;
use frontend\models\Vehiculo;
use frontend\models\Conductor;

class SiteController extends Controller
{
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
        
    public function actionTurnos()
    {
        $fechaHoy = date('Y-m-d');
        $tabla = new Turno;
        $usuarioId= Yii::$app->session->get('usuario_id');
        $admin= Yii::$app->session->get('usuario_admin');
        
        
       if ($admin == 1){
           $model = $tabla ->find()->where(['turno_fecha'=> $fechaHoy])->orderBy(['turno_hora' => SORT_ASC])->all();
           return $this->render("turnos-admin", ["model" => $model]);
       }else{
            $model = $tabla ->find()->where(['usuario_id' => $usuarioId])->andWhere(['turno_fecha' => $fechaHoy])->orderBy(['turno_hora' => SORT_ASC])->all(); 
            return $this->render("turnos-usuario", ["model" => $model]);
       }
    } 
        
    public function actionElegirFecha($fecha = null)
{
    $model = new Turno();
    $usuarioId = Yii::$app->session->get('usuario_id');
    $admin = Yii::$app->session->get('usuario_admin');
    
    if ($fecha === null && $model->load(Yii::$app->request->post())) {
        $fecha = $model->turno_fecha;
    }
    
    if ($fecha !== null) {
        if ($admin == 1) {
            $turnosSeleccionados = Turno::find()
                ->where(['turno_fecha' => $fecha])
                ->orderBy(['turno_hora' => SORT_ASC])
                ->all();
            return $this->render('elegir-fecha-admin', [
                'turnosSeleccionados' => $turnosSeleccionados,
            ]);
        } else {
            $turnosSeleccionados = Turno::find()
                ->where(['turno_fecha' => $fecha, 'usuario_id' => $usuarioId])
                ->orderBy(['turno_hora' => SORT_ASC])
                ->all();
            return $this->render('elegir-fecha-usuario', [
                'turnosSeleccionados' => $turnosSeleccionados,
            ]);
        }
    }
    
    return $this->redirect(['site/turnos']);
}

    
    public function actionEstado()
{
    $turnoId = Yii::$app->request->post('turno_id');
    $turnoEstado = Yii::$app->request->post('turno_estado');

    if ($turnoId && $turnoEstado) {
        $turno = Turno::findOne($turnoId);
        if ($turno) {
            $turno->turno_estado = $turnoEstado;
            if ($turno->save()) {
                Yii::$app->session->setFlash('success', 'El estado del turno ha sido actualizado.');
            } else {
                Yii::$app->session->setFlash('error', 'No se pudo actualizar el estado del turno.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Turno no encontrado.');
        }
    } else {
        Yii::$app->session->setFlash('error', 'Datos inválidos.');
    }

    return $this->redirect(['site/turnos']);
}


public function actionAgendarTurno()
{
    $model = new Turno();
    $usuarioId = Yii::$app->session->get('usuario_id');
    $admin = Yii::$app->session->get('usuario_admin');

    if ($admin == 1) {
        $clientes = Clientes::find()
            ->select(['cliente_razon_social'])
            ->indexBy('usuario_id')
            ->column();
        $vehiculo = Vehiculo::find()
            ->select(['vehiculo_patente'])
            ->indexBy('vehiculo_patente')
            ->column();
        $conductor = Conductor::find()
            ->select(['conductor_nombre'])
            ->indexBy('conductor_dni')
            ->column();
    } else {
        $cuit = Clientes::find()
            ->select(['cliente_cuit'])
            ->where(['usuario_id' => $usuarioId])
            ->scalar();
        $vehiculo = Vehiculo::find()
            ->select(['vehiculo_patente'])
            ->where(['cliente_cuit' => $cuit])
            ->indexBy('vehiculo_patente')
            ->column();
        $conductor = Conductor::find()
            ->select(['conductor_nombre'])
            ->where(['cliente_cuit' => $cuit])
            ->indexBy('conductor_nombre')
            ->column();
    }

    if ($this->request->isPost && $model->load($this->request->post())) {
        $existingTurno = Turno::findOne([
            'turno_hora' => $model->turno_hora,
            'turno_fecha' => $model->turno_fecha,
        ]);
        if ($existingTurno) {
            // Mostrar un mensaje de error indicando que ya existe un turno en esa hora y día
            $model->addError('turno_fecha', 'Ya existe un turno agendado para el dia y fecha solicitado.');
        } else {
            if ($admin != 1) {
                $model->usuario_id = $usuarioId;
            }
            $model->save();
            return $this->redirect(['turnos']);
        }
    }

    if ($admin == 1) {
        return $this->render('agendar-turno-admin', [
            'model' => $model,
            'clientes' => $clientes,
            'vehiculo' => $vehiculo,
            'conductor' => $conductor,
        ]);
    } else {
        return $this->render('agendar-turno-usuario', [
            'model' => $model,
            'conductor' => $conductor,
            'vehiculo' => $vehiculo,
        ]);
    }
}
     
    public function actionReprogramarTurno($turnoId)
    {
            $model = $this->findTurno($turnoId); 
            if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
             $existingTurno = Turno::findOne([
                'turno_hora' => $model->turno_hora,
                'turno_fecha' => $model->turno_fecha,
            ]);
               if ($existingTurno) {
                // Mostrar un mensaje de error indicando que ya existe un turno en esa hora y día
                $model->addError('turno_fecha', 'Ya existe un turno agendado para el dia y fecha solicitado.');
            } else {
                     $model->turno_estado = 1;
                     $model->save(); 
                     return $this->redirect('turnos');
            }}} 
            return $this->render('reprogramar-turno', ['model' => $model]);
    }
    

    public function actionEliminarTurno($turnoId)
    {
            $this->findTurno($turnoId)->delete();

            return $this->redirect(['turnos']);
    }

    protected function findTurno($turnoId)
    {
            if (($model = Turno::findOne($turnoId)) !== null) {
                return $model;
            }
            throw new NotFoundHttpException('El turno no existe.');
    }
    
    
  public function actionListaTurnos()
{
    $usuarioId = Yii::$app->session->get('usuario_id');
    $admin = Yii::$app->session->get('usuario_admin');

    if ($admin == 1) {
        $model = Turno::find()->orderBy(['turno_id' => SORT_ASC, 'turno_hora' => SORT_ASC])->all();
            
    } else {
        $model = Turno::find()->where(['usuario_id' => $usuarioId])
            ->orderBy(['turno_id' => SORT_ASC, 'turno_hora' => SORT_ASC])->all();
    }

    return $this->render("lista-turnos", ["model" => $model]);
}




public function actionEditarObservacion($turno_id, $fecha)
{
    // Encuentra el turno por su ID
    $turno = Turno::findOne($turno_id);

    if ($turno === null) {
        throw new NotFoundHttpException('El turno no existe.');
    }

    // Si el formulario se envía
    if ($turno->load(Yii::$app->request->post()) && $turno->save()) {
        Yii::$app->session->setFlash('success', 'Guardado correctamente.');
        // Redirige a la acción elegir-fecha con la fecha del turno como parámetro
        return $this->redirect(['site/elegir-fecha', 'fecha' => $fecha]);
    }

    // Renderiza la vista de edición de observaciones
    return $this->render('editar-observacion', [
        'turno' => $turno,
    ]);
}
   
    public function behaviors()
        {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => [],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
        }

    public function actions()
        {
            return [
                'error' => [
                    'class' => \yii\web\ErrorAction::class,
                ],
                'captcha' => [
                    'class' => \yii\captcha\CaptchaAction::class,
                    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                ],
            ];
        }

    public function actionIndex()
    {
        return $this->redirect('site/turnos');
    }

    public function actionLogin()
        {
            

            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            }

            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }

    public function actionLogout()
        {
            Yii::$app->user->logout();

            return $this->goHome();
        }

  public function actionSignup()
{
    $model = new SignupForm();
    $cliente = new Clientes();

    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        $usuario_id = $model->signup();
        if ($usuario_id) {
            Yii::$app->session->setFlash('success', '¡Gracias por registrarte! Se ha enviado el correo de verificación de cuenta al e-mail registrado.');

            // Guardar los datos adicionales en la tabla clientes
            $cliente->cliente_razon_social = $model->cliente_razon_social;
            $cliente->cliente_cuit = $model->cliente_cuit;
            $cliente->cliente_telefono = $model->cliente_telefono;
            $cliente->cliente_mail = $model->cliente_mail;
            $cliente->usuario_id = $usuario_id;
            
            if ($cliente->save()) {
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Error al guardar los datos del cliente.');
            }
        }
    }

    return $this->render('signup', [
        'model' => $model,
        'cliente' => $cliente,
    ]);
}
    public function actionRequestPasswordReset()
        {
            $model = new PasswordResetRequestForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->sendEmail()) {
                    Yii::$app->session->setFlash('success', 'Revisa tu e-mail para más instrucciones.');

                    return $this->goHome();
                }

                Yii::$app->session->setFlash('error', 'Lo sentimos, no podemos restablecer la contraseña de la dirección de correo electrónico proporcionada.');
            }

            return $this->render('requestPasswordResetToken', [
                'model' => $model,
            ]);
        }


    public function actionResetPassword($token)
        {
            try {
                $model = new ResetPasswordForm($token);
            } catch (InvalidArgumentException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
                Yii::$app->session->setFlash('success', 'Nueva contraseña guardada.');

                return $this->goHome();
            }

            return $this->render('resetPassword', [
                'model' => $model,
            ]);
        }

    public function actionVerifyEmail($token)
        {
            try {
                $model = new VerifyEmailForm($token);
            } catch (InvalidArgumentException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
            if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', '¡Tu e-mail ha sido confirmado!');
                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Lo sentimos, no podemos verificar su cuenta con el token proporcionado.');
            return $this->goHome();
        }

    public function actionResendVerificationEmail()
        {
            $model = new ResendVerificationEmailForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->sendEmail()) {
                    Yii::$app->session->setFlash('success', 'Revisa tu e-mail para más instrucciones.');
                    return $this->goHome();
                }
                Yii::$app->session->setFlash('error', 'Lo sentimos, no podemos reenviar el correo electrónico de verificación para la dirección de correo electrónico proporcionada.');
            }

            return $this->render('resendVerificationEmail', [
                'model' => $model
            ]);
        }
  
   
    


    



}
