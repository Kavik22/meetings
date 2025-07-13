<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Event $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="event-view">
    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): 
        $parts = explode("_", $type);
        $alertType = array_pop($parts) == 'success' ? 'success' : 'danger';
        ?>
        <div class="alert alert-<?= $alertType ?>">
            <?= $message ?>
        </div>
    <?php endforeach; ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            // 'annotation',
            'description:ntext',
            // 'location',
            'address',
            // 'contact',
            'date_of_event',
            [
                'attribute' => 'participants',
                'label' => 'Количество участников',
                'value' => function ($model) {
                return count($model->participants);
            }
            ],
            [
                'attribute' => 'participants',
                'label' => 'Участники',
                'format' => 'html',
                'value' => function ($model) {
                $output = [];
                foreach ($model->participants as $participant) {
                    $output[] = "$participant->name: $participant->email, $participant->tag;";
                }
                return implode('<br>', $output);
            }
            ],
            [
                'attribute' => 'image',
                'value' => !empty($model->images) ? \Yii::getAlias('@web') . '/' . $model->images[0]->path : null,
                'format' => ['image', ['width' => '100', 'height' => '100']],
            ],
        ],
    ]) ?>

</div>