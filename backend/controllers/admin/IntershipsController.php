<?php

namespace app\controllers\admin;

use Yii;
use yii\helpers\Url;
use app\models\Country;
use yii\helpers\ArrayHelper;
use app\models\Intership;
use app\models\Image;
use yii\web\UploadedFile;

class IntershipsController extends AdminController
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect(['admin/interships/upcoming']);
    }


    public function actionUpcoming(){
        $interships = Intership::find()->where(['>=', 'date_start', date('Y-m-d')])->orderBy(['date_start' => SORT_ASC])->all();
        return $this->render('index',[
            'interships' => $interships,
            'status' => 'upcoming',
        ]);
    }
    
    public function actionPast(){
        $interships = Intership::find()->where(['<', 'date_start', date('Y-m-d')])->orderBy(['date_start' => SORT_DESC])->all();
        return $this->render('index', [
            'interships' => $interships,
            'status' => 'past',
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $intership = $this->findModel($id);
        $countries = ArrayHelper::map(Country::find()->all(), 'id', 'title');

        if (isset($intership->images[0])){
            $image = $intership->images[0];
        }else{
            $image = new Image();
        }

        if ($this->request->isPost && $intership->load($this->request->post())) {
            $image->imageFile = UploadedFile::getInstance($image, 'imageFile');
                
            if ($image->imageFile && $image->load($this->request->post())) {
                if ($this->saveImage($image)) {
                    if (isset($intership->images[0])){
                        $old_image = $intership->images[0];
                        $intership->unlink('images', $old_image);
                    }
                    $intership->link('images', $image);
                }
            }
            
            if ($this->request->post('Intership')['country_id']){
                $intership->country_id = $this->request->post('Intership')['country_id'];
            }
            
            $intership->save();

            return $this->redirect(['admin/interships/update/' . $intership->id]);
        }

        return $this->render('update', [
            'intership' => $intership,
            'image' => $image,
            'countries' => $countries,
        ]);
    }
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['admin/interships']);
    }

    public function actionCreate()
    {
        $intership = new Intership();
        $image = new Image();
        $countries = ArrayHelper::map(Country::find()->all(), 'id', 'title');
        
        if ($this->request->isPost) {
            $intership->load($this->request->post());
            if ($intership->save()) {
                if ($image->load($this->request->post())) {
                    if ($this->saveImage($image)) {
                        $intership->link('images', $image);
                    }
                }
                return $this->redirect(['admin/interships/' . $intership->id]);
            }
        } else {
            $intership->loadDefaultValues();
        }

        return $this->render('create', [
            'intership' => $intership,
            'image' => $image,
            'countries' => $countries,
        ]);
    }

    protected function saveImage($image){
        $image->imageFile = UploadedFile::getInstance($image, 'imageFile');
        if ($image->imageFile) {
            $path = 'uploads/'; // Путь для сохранения изображений
            $filename = \Yii::$app->security->generateRandomString() . '.' . $image->imageFile->extension;
            $image->path = $path . $filename;
            $image->title = $filename;
            if ($image->imageFile->saveAs($image->path)) {
                if ($image->save(false)) {
                    \Yii::$app->session->setFlash('success', 'Изображение успешно сохранено!');
                    return true;
                } else {
                    \Yii::$app->session->setFlash('error', 'Ошибка при сохранении изображения в базу данных!');
                    return false;
                }
            } else {
                \Yii::$app->session->setFlash('error', 'Ошибка при сохранении изображения на сервер!');
                return false;
            }
        }
        return false;
    }
    
    protected function findModel($id)
    {
        if (($model = Intership::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}