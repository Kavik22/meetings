<?php

namespace app\controllers\admin;

use Yii;
use yii\helpers\Url;

use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Event;
use app\models\Image;
use app\models\Participant;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\clients\TelegramClient;
use app\clients\VKClient;
use app\clients\VKClientCopy;

class EventsController extends AdminController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function actionIndex()
    {  
        $this->redirect(['admin/events/upcoming']);
    }

    public function actionUpcoming(){
        $events = Event::find()->where(['>=', 'date_of_event', date('Y-m-d')])->orderBy(['date_of_event' => SORT_ASC])->all();
        return $this->render('index',[
            'events' => $events,
            'status' => 'upcoming',
        ]);
    }
    
    public function actionPast(){
        $events = Event::find()->where(['<', 'date_of_event', date('Y-m-d')])->orderBy(['date_of_event' => SORT_DESC])->all();
        return $this->render('index', [
            'events' => $events,
            'status' => 'past',
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => Event::findOne(['id' => $id]),
        ]);
    }

    public function actionUpdate($id)
    {
        $event = Event::findOne(['id' => $id]);
        
        if (isset($event->images[0])){
            $image = $event->images[0];
        }else{
            $image = new Image();
        }
        
        if ($this->request->isPost && $event->load($this->request->post())) {
            $image->imageFile = UploadedFile::getInstance($image, 'imageFile');
            if ($image->imageFile && $image->load($this->request->post())) {        
                if ($this->saveImage($image)) {
                    if (isset($event->images[0])){
                        $old_image = $event->images[0];
                        $event->unlink('images', $old_image);
                    }
                    $event->link('images', $image);
                }
            }

            $event->save();
            
            $this->updateSocialPosts($event, $image);

            return $this->redirect(['admin/events/' . $event->id]);
        }

        return $this->render('update', [
            'event' => $event,
            'image' => $image,
        ]);
    }

    public function updateSocialPosts($event, $image){
        $image_path = $image->path ? Url::base(true) . '/' . $image->path : null;
        $new_text_message = $event->description . "\nНе забудь зарегестрироваться на мероприятие: " . Url::base(true) . "/events/" . $event->id;

        $tg_client = new TelegramClient();
        $tg_response = $tg_client->updateMessage($event->tg_message_id, $new_text_message, $image_path, $event->is_media);
        if (isset($tg_response)) {
            $event->tg_message_id = $tg_response["result"]['message_id'];
            $event->is_media = isset($image_path) ? true : false;
        }

        $vk_client = new VKClient();
        $vk_client->updatePost($event->vk_post_id, $new_text_message, $image_path);

        $event->save();
    }
    
    public function actionDelete($id)
    {
        $event = Event::findOne(['id' => $id]);   $event = Event::findOne(['id' => $id]);
        
        $tg_client = new TelegramClient();
        $tg_client->deleteMessage($event->tg_message_id);

        $vk_client = new VKClient();
        $vk_client->deletePost($event->vk_post_id);


        $event->delete();

        return $this->redirect(['admin/events/upcoming']);
    }

    public function actionCreate()
    {
        $event = new Event();
        $image = new Image();
        if ($this->request->isPost) {
            $event->load($this->request->post());
            if ($event->save()) {
                if ($image->load($this->request->post())) {
                    
                    if ($this->saveImage($image)) {
                        $event->is_media = true;
                        $event->link('images', $image);
                    }
                }
                $photo_url = $image->path ? Url::base(true) . '/' . $image->path : null;
                $text_message =  $event->description . "\nНе забудь зарегестрироваться на мероприятие: " . Url::base(true) . "/events/" . $event->id;
                
                
                $tg_client = new TelegramClient();
                $tg_response = $tg_client->sendMessage( $text_message, $photo_url);
                $event->tg_message_id = $tg_response["result"]['message_id'];

                $vk_client = new VKClient();
                $vk_response = $vk_client->publishPost($text_message, $photo_url);
                $event->vk_post_id = $vk_response['response']['post_id'];
                $event->save();
                
                return $this->redirect(['admin/events/' . $event->id]);
            }

        } else {
            $event->loadDefaultValues();
        }

        return $this->render('create', [
            'event' => $event,
            'image' => $image,
        ]);
    }

    // public function actionTest(){
    //     $photo_url = "https://img2.akspic.ru/attachments/originals/9/9/2/0/80299-leopard-zhivaya_priroda-nazemnye_zhivotnye-bakenbardy-tigr-3840x2160.jpg"; 
    //     $photo_url_2 = "https://i.pinimg.com/736x/af/7b/98/af7b98f33b8a21efc5846c2676fff1c0.jpg";
    //     // $vk_client = new VKClient();
    //     // $response = $vk_client->publishPost("test message",$photo_url);
    //     // $response = $vk_client->publishPost("test message", null);

    //     // $vk_client->deletePost($response['response']['post_id']);

    //     $telegram = new TelegramClient();
    //     // $response = $telegram->sendMessage( "test message", $photo_url);
    //     $response = $telegram->updateMessage(103, 'new old message', $photo_url, true);
    //     dd( $response );
    //     // $response = $telegram->sendMessage( "test message");
    //     // $telegram->updateMessage(97, 'new_message');
    //     // $response = $telegram->updateMessage(85, 'updated');
        
                
    //     return $this->redirect(['admin/events/1']);
    // }

    protected function saveImage($image){
        $image->imageFile = UploadedFile::getInstance($image, 'imageFile');
        if ($image->imageFile) {
            $path = 'uploads/';
            $filename = \Yii::$app->security->generateRandomString() . '.' . $image->imageFile->extension;
            $image->path = $path . $filename;
            $image->title = $filename;
            $image->save();
            if ($image->imageFile->saveAs($image->path)) {
                if ($image->save(false)) {
                    \Yii::$app->session->setFlash('img_save_success', 'Изображение успешно сохранено!');
                    return true;
                } else {
                    \Yii::$app->session->setFlash('img_save_error', 'Ошибка при сохранении изображения в базу данных!');
                    return false;
                }
            } else {
                \Yii::$app->session->setFlash('img_save_error', 'Ошибка при сохранении изображения на сервер!');
                return false;
            }
        }
        return false;
    }



    
    protected function findModel($id)
    {
        if (($model = Event::findOne(['id' => $id])) !== null) {   if (($model = Event::findOne(['id' => $id])) !== null) {
            return $model;
        }

    }
}
}