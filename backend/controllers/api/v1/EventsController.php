<?php 

namespace app\controllers\api\v1;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use app\models\Event;
use app\models\Participant;
use yii\filters\Cors;


use Yii;

class EventsController extends ActiveController
{
    public $modelClass = 'app\models\Event';


    public function actionOptions()
    {
        return '';
    }

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
        } elseif ($data instanceof Event) {
            return $this->formatModel($data);
        }
        return parent::serializeData($data);
    }

    private function formatModel($model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'date_of_event' => $model->date_of_event,
            'annotation' => $model->annotation,
            'description' => $model->description,
            'address' => $model->address,
            'participants' => $model->participants,
            'image_path' => $model->images ? $model->images[0]->path : null,
        ];
        
    }


    public function actions()
    {
        $actions = parent::actions();

        // Customize the index action
        $actions['index']['prepareDataProvider'] = function () {
            return new \yii\data\ActiveDataProvider([
                'query' => Event::find()->where(['>=', 'date_of_event', date('Y-m-d')]),
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort' => [
                    'defaultOrder' => ['date_of_event' => SORT_ASC],
                ],
            ]);
        };
        return $actions;
    }

    public function actionPast(){
        return new \yii\data\ActiveDataProvider([
            'query' => Event::find()->where(['<', 'date_of_event', date('Y-m-d')]),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['date_of_event' => SORT_DESC],
            ],
        ]);
    }

    public function actionSearch()
    {
        $query = Event::find()->with(['participants'])->with('images');
        $params = $this->getQueryParams();
        
        if (isset($params['location'])) {
            $query->andWhere(['location' => $params['location']]);
        }

        if (isset($params['address'])) {
            $query->andWhere(['like', 'address', $params['address']]);
        }

        if (isset($params['date_from'])) {
            $query->andWhere(['>=', 'date_of_event', $params['date_from']]);
        }

        if (isset($params['date_to'])) {
            $query->andWhere(['<=', 'date_of_event', $params['date_to']]);
        }   

        if (isset($params['title'])) {
            $query->andWhere(['like', 'title', $params['title']]);
        }

        if (isset($params['annotation'])) {
            $query->andWhere(['like', 'annotation', $params['annotation']]);
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



    public function actionAddParticipant($id)
    {
        try {
            $event = Event::findOne($id);

    
            $participant = Participant::findOne(['email' => Yii::$app->request->post('email')]);

            if (!$participant){
                $participant = new Participant();

                if (Yii::$app->request->post('email')){
                    $participant->email = Yii::$app->request->post('email');
                }
            }
            
            if (Yii::$app->request->post('name')){
                $participant->name = Yii::$app->request->post('name');
            }
            if (Yii::$app->request->post('tag')){
                $participant->tag = Yii::$app->request->post('tag');
            }
            if (Yii::$app->request->post('found_out_about_us')){
                if (Yii::$app->request->post('found_out_about_us') === 'Other'){
                    $participant->found_out_about_us = Yii::$app->request->post('other_source');
                } else {
                    $participant->found_out_about_us = Yii::$app->request->post('found_out_about_us');
                }
            }
            if (Yii::$app->request->post('direction_of_study')){
                $participant->direction_of_study = Yii::$app->request->post('direction_of_study');
            }
            $participant->save();

            $event->link('participants', $participant);
            $event->save();
            return $event;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function actionParticipants($id)
    {
        $model = Event::findOne($id);
        return $model->participants;
    }
    
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