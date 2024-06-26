<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use Twilio\Rest\Client;
use frontend\models\Conductor;

class TrackController extends Controller
{
    // Método para enviar el enlace de seguimiento por SMS
    public function actionSendTrackingLink($phoneNumber)
    {
        // Generar la URL de seguimiento única
        $trackingUrl = Url::to(['track/get-location', 'id' => Yii::$app->security->generateRandomString()], true);

        

        // Crear el cliente Twilio
        $twilio = new Client($sid, $token);

        // Enviar el mensaje SMS con el enlace de seguimiento
        $message = $twilio->messages->create(
            $phoneNumber,
            [
                'from' => $twilioNumber,
                'body' => "Por favor, haz clic en este enlace para compartir tu ubicación: $trackingUrl"
            ]
        );

        // Mostrar un mensaje de éxito usando flash
        Yii::$app->session->setFlash('success', 'Enlace de seguimiento enviado correctamente por SMS.');
        
        // Redirigir a una acción después de enviar el SMS (ajusta según tu flujo)
        return $this->redirect(['site/index']); // Ejemplo: redirigir a la página principal
    }

    // Acción para mostrar la página de obtención de ubicación
    public function actionGetLocation($id)
    {
        return $this->render('get-location', ['id' => $id]);
    }

    // Acción para guardar la ubicación recibida del usuario
    public function actionSaveLocation()
    {
        // Obtener los datos de ubicación enviados por POST
        $data = Yii::$app->request->post();
        $trackingId = $data['id'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];

        // Guardar la ubicación en la base de datos u otro almacenamiento
        // Aquí se simula el guardado en una tabla 'locations'
        $db = Yii::$app->db;
        $command = $db->createCommand()->insert('locations', [
            'tracking_id' => $trackingId,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
        $command->execute();

        // Responder con un mensaje JSON para indicar el éxito
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success'];
    }

    // Acción para ver la ubicación guardada
    public function actionViewLocation($id)
    {
        // Consultar la ubicación desde la base de datos
        $location = (new \yii\db\Query())
            ->select(['latitude', 'longitude'])
            ->from('locations')
            ->where(['tracking_id' => $id])
            ->one();

        // Si se encuentra la ubicación, mostrar la vista con los datos
        if ($location) {
            return $this->render('view-location', [
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
            ]);
        } else {
            throw new \yii\web\NotFoundHttpException('Ubicación no encontrada.');
        }
    }
}