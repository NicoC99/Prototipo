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
use frontend\models\Horarios;
use frontend\models\Productos;

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
        $horarios = Horarios::find()->orderBy('horario_hora ASC')->all();
    $horariosArray = [];
    foreach ($horarios as $horario) {
        $horariosArray[$horario->horario_hora] = $horario->horario_hora;
    }
        
       if ($admin == 1){
           $turnos = $tabla ->find()->where(['turno_fecha'=> $fechaHoy])->orderBy(['turno_hora' => SORT_ASC])->all();
           return $this->render("turnos-admin", ["turnos" => $turnos,  'horarios' => $horariosArray,]);
       }else{
            $turnos = $tabla ->find()->where(['usuario_id' => $usuarioId])->andWhere(['turno_fecha' => $fechaHoy])->orderBy(['turno_hora' => SORT_ASC])->all(); 
            return $this->render("turnos-usuario", ["turnos" => $turnos,  'horarios' => $horariosArray,]);
       }
    } 
        
public function actionElegirFecha($fecha = null)
{
    $turnos = new Turno();
    $usuarioId = Yii::$app->session->get('usuario_id');
    $admin = Yii::$app->session->get('usuario_admin');
    $horarios = Horarios::find()->orderBy('horario_hora ASC')->all();
    $horariosArray = [];
    foreach ($horarios as $horario) {
        $horariosArray[$horario->horario_hora] = $horario->horario_hora;
    }
    if ($fecha === null && $turnos->load(Yii::$app->request->post())) {
        $fecha = $turnos->turno_fecha;
    }
    
    if ($fecha !== null) {
        if ($admin == 1) {
            $turnosSeleccionados = Turno::find()
                ->where(['turno_fecha' => $fecha])
                ->orderBy(['turno_hora' => SORT_ASC])
                ->all();
            return $this->render('elegir-fecha-admin', [
                'turnosSeleccionados' => $turnosSeleccionados, 'fecha' => $fecha, 'horarios' => $horariosArray,
            ]);
        } else {
            $turnosSeleccionados = Turno::find()
                ->where(['turno_fecha' => $fecha, 'usuario_id' => $usuarioId])
                ->orderBy(['turno_hora' => SORT_ASC])
                ->all();
            return $this->render('elegir-fecha-usuario', [
                'turnosSeleccionados' => $turnosSeleccionados, 'fecha' => $fecha, 'horarios' => $horariosArray,
            ]);
        }
    }
    
    // Si no se especifica fecha y no se carga desde el formulario, redirigir o mostrar un mensaje de error
    Yii::$app->session->setFlash('error', 'Debe seleccionar una fecha válida.');
    return $this->redirect(['site/index']); // Puedes cambiar esto según tu flujo de la aplicación
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
    } 
    return $this->redirect(['site/elegir-fecha', 'fecha' => $turno->turno_fecha]);
}


public function actionAgendarTurno()
{
    $turno = new Turno();
    $usuarioId = Yii::$app->session->get('usuario_id');
    $admin = Yii::$app->session->get('usuario_admin');
    $horarios = Horarios::find()->orderBy('horario_hora ASC')->all();
    $horariosArray = [];
    foreach ($horarios as $horario) {
        $horariosArray[$horario->horario_hora] = $horario->horario_hora;
    }
    $productos = Productos::find()->orderBy('producto_nombre ASC')->all();
    $productosArray = [];
    foreach ($productos as $producto) {
        $productosArray[$producto->producto_nombre] = $producto->producto_nombre;
    }
    
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
            ->indexBy('conductor_dni')
            ->column();
    }

    if ($this->request->isPost && $turno->load($this->request->post())) {
        $existingTurno = Turno::findOne([
            'turno_hora' => $turno->turno_hora,
            'turno_fecha' => $turno->turno_fecha,
        ]);
        if ($existingTurno) {
            // Mostrar un mensaje de error indicando que ya existe un turno en esa hora y día
            $turno->addError('turno_fecha', 'Ya existe un turno agendado para el dia y fecha solicitado.');
        } else {
            if ($admin != 1) {
                $turno->usuario_id = $usuarioId;
            }
            $turno->save();
            Yii::$app->session->setFlash('success', 'Turno agendado con éxito.');
            return $this->redirect(['turnos']);
        }
    }

    if ($admin == 1) {
        return $this->render('agendar-turno-admin', [
            'turno' => $turno,
            'clientes' => $clientes,
            'vehiculo' => $vehiculo,
            'conductor' => $conductor,
            'horarios' => $horariosArray,
            'productos' => $productosArray,
        ]);
    } else {
        return $this->render('agendar-turno-usuario', [
            'turno' => $turno,
            'conductor' => $conductor,
            'vehiculo' => $vehiculo,
            'horarios' => $horariosArray,
            'productos' => $productosArray,
        ]);
    }
}
     
public function actionReprogramarTurno($turnoId)
{
    $turno = Turno::findOne($turnoId);

    if (!$turno) {
        Yii::$app->session->setFlash('error', 'El turno especificado no existe.');
        return $this->redirect(['site/turnos']);
    }

    if ($this->request->isPost && $turno->load($this->request->post())) {
        // Validar si ya existe un turno para la misma fecha y hora
        $existingTurno = Turno::find()
            ->where([
                'and',
                ['!=', 'turno_id', $turnoId], // Excluir el turno actual
                ['turno_hora' => $turno->turno_hora],
                ['turno_fecha' => $turno->turno_fecha],
            ])
            ->one();

        if ($existingTurno) {
            // Mostrar un mensaje de error indicando que ya existe un turno en esa hora y día
            Yii::$app->session->setFlash('error', 'Ya existe un turno agendado para la fecha y hora seleccionada.');
            return $this->redirect(['site/elegir-fecha', 'fecha' => $turno->turno_fecha]);
        } else {
            // Guardar el turno reprogramado
            if ($turno->save()) {
                Yii::$app->session->setFlash('success', 'El turno se ha reprogramado correctamente.');
                return $this->redirect(['site/elegir-fecha', 'fecha' => $turno->turno_fecha]);
            }
        }
    }
    
    // Si no es una solicitud POST o no se carga correctamente el turno
    return $this->redirect(['site/elegir-fecha', 'fecha' => $turno->turno_fecha]);
}
   
    public function actionEliminarTurno($turnoId)
    {
            $this->findTurno($turnoId)->delete();
            Yii::$app->session->setFlash('success', 'Turno cancelado.');    
            return $this->redirect(['turnos']);
    }

    protected function findTurno($turnoId)
    {
            if (($turno = Turno::findOne($turnoId)) !== null) {
                return $turno;
            }
            throw new NotFoundHttpException('El turno no existe.');
    }
    
    
  public function actionListaTurnos()
{
    $usuarioId = Yii::$app->session->get('usuario_id');
    $admin = Yii::$app->session->get('usuario_admin');

    if ($admin == 1) {
        $turnos = Turno::find()->orderBy(['turno_id' => SORT_ASC, 'turno_hora' => SORT_ASC])->all();
            
    } else {
        $turnos = Turno::find()->where(['usuario_id' => $usuarioId])
            ->orderBy(['turno_id' => SORT_ASC, 'turno_hora' => SORT_ASC])->all();
    }

    return $this->render("lista-turnos", ['turnos' => $turnos]);
}




public function actionEditarObservacion()
{
    if (Yii::$app->request->isPost) {
        $turno_id = Yii::$app->request->post('turno_id');
        $turno = Turno::findOne($turno_id);

        if ($turno === null) {
            throw new NotFoundHttpException('El turno no existe.');
        }

        $turno->turno_observacion = Yii::$app->request->post('turno_observacion');
        if ($turno->save()) {
            Yii::$app->session->setFlash('success', 'Observación guardada correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo guardar la observación.');
        }

        return $this->redirect(['site/elegir-fecha', 'fecha' => $turno->turno_fecha]);
    }

    throw new BadRequestHttpException('Solicitud no válida.');
}
   
public function actionHorarios(){
    $horarios = Horarios::find()->all();
    
    
    return $this->render('horarios', ['horarios' => $horarios]);
}
public function actionEliminarHorario($horarioId)
        {
        if (($horario = Horarios::findOne($horarioId)) !== null){
           $horario->delete();
           Yii::$app->session->setFlash('success', 'Horario eliminado correctamente.');
        }
        
        return $this->redirect(['horarios']);
        }
public function actionAgregarHorario()
{
    $model = new Horarios();

    if (Yii::$app->request->post()) {
        $hora = Yii::$app->request->post('hora');
        $minutos = Yii::$app->request->post('minutos');
        $model->horario_hora = $hora . ':' . $minutos;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Horario agregado correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Error al agregar el horario.');
        }
    }

    return $this->redirect(['horarios']);
}

public function actionProductos()
{
    $model = new Productos(); // Crear una instancia del modelo Productos
    $productos = Productos::find()->all();
    
    return $this->render('productos', [
        'model' => $model,   // Pasar el modelo a la vista
        'productos' => $productos,
    ]);
}

public function actionEliminarProducto($productoId)
        {
        if (($producto = Productos::findOne($productoId)) !== null){
           $producto->delete();
           Yii::$app->session->setFlash('success', 'Horario eliminado correctamente.');
        }
        
        return $this->redirect(['productos']);
        }
        
        
public function actionAgregarProducto()
{
    $model = new Productos();

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        Yii::$app->session->setFlash('success', 'Producto agregado correctamente.');
    } else {
        Yii::$app->session->setFlash('error', 'Error al agregar el producto.');
    }

    return $this->redirect(['productos']);
}

public function actionUbicacion()
    {
        return $this->render('ubicacion');
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
    if (Yii::$app->user->isGuest) {
        return $this->redirect(['site/login']);
    } else {
        return $this->redirect(['site/turnos']);
    }
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
            
            if ($cliente->save()&& $model->sendEmail()) {
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
