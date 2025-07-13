<?php 

namespace app\controllers\api\v1;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use app\models\Intership;
use Yii;

class IntershipsController extends ActiveController
{
    public $modelClass = 'app\models\intership';

    public function behaviors()
    {
        $behaviors = parent::behaviors();


        // Configure content negotiator
        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => \yii\web\Response::FORMAT_JSON,
        ];
        return $behaviors;
    }

    protected function serializeData($data)
    {
        if ($data instanceof ActiveDataProvider) {
            $models = $data->getModels();
            $result = [];
            foreach ($models as $model) {
                $result[] = $this->formatModel($model);
            }
            return $result;
        } elseif ($data instanceof intership) {
            return $this->formatModel($data);
        }
        return parent::serializeData($data);
    }

    private function formatModel($model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'link_to_ru_site' => $model->link_to_ru_site,
            'annotation' => $model->annotation,
            'description' => $model->description,
            'price' => $model->price,
            'date_start' => $model->date_start,
            'date_end' => $model->date_end,
            'country' => $model->country,
            'contact' => $model->contact,
            'image_path' => $model->images ? $model->images[0]->path : null,
        ];
    }

    public function actionSearch()
    {
        $query = intership::find()->with('images');
        $params = $this->getQueryParams();
        
        if (isset($params['title'])) {
            $query->andWhere(['like', 'title', $params['title']]);
        }
        
        if (isset($params['annotation'])) {
            $query->andWhere(['like', 'annotation', $params['annotation']]);
        }

        if (isset($params['date_start'])) {
            $query->andWhere(['>=', 'date_start', $params['date_start']]);
        }

        if (isset($params['date_end'])) {
            $query->andWhere(['<=', 'date_end', $params['date_end']]);
        }   
        
        if (isset($params['country_id'])) {
            $query->andWhere(['country_id' => $params['country_id']]);
        }

        if (isset($params['contact'])) {
            $query->andWhere(['contact' => $params['contact']]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,   
            ],
        ]);
    }

    // public function actionAddParticipant($id)
    // {
    //     try {
    //         $intership = intership::findOne($id);

    //         $participant = Participant::findOne(['phone' => Yii::$app->request->post('phone')]);

    //         if (!$participant){
    //             $participant = new Participant();
    //         }
            
    //         if (Yii::$app->request->post('name')){
    //             $participant->name = Yii::$app->request->post('name');
    //         }
    //         if (Yii::$app->request->post('email')){
    //             $participant->email = Yii::$app->request->post('email');
    //         }
    //         if (Yii::$app->request->post('telegram')){
    //             $participant->telegram = Yii::$app->request->post('telegram');
    //         }
    //         if (Yii::$app->request->post('phone')){
    //             $participant->phone = Yii::$app->request->post('phone');
    //         }
    //         if (Yii::$app->request->post('whatsapp')){
    //             $participant->whatsapp = Yii::$app->request->post('whatsapp');
    //         }
    //         $participant->save();

    //         $intership->save();
    //         return $intership;
    //     } catch (\Exception $e) {
    //         return $e->getMessage();
    //     }
    // }




    
    public function getQueryParams(){
                
        $url = explode('?', Yii::$app->request->url);
        $params = [];
        if (count($url) > 1){
            $url = explode('&', $url[1]);
            foreach ($url as $item){
                $item = explode('=', $item);
                $params[$item[0]] = $item[1];
            }
        }
        return $params;
    }
}