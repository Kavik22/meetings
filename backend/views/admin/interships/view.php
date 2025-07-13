<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\intership $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'interships', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="intership-view">

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
            'annotation',
            'description:ntext',
            'price',
            'contact',
            'date_start',
            'date_end',
            // 'country.title',
            [
                'attribute' => 'image',
                'value' => !empty($model->images) ? \Yii::getAlias('@web') . '/' . $model->images[0]->path : null,
                'format' => ['image', ['width' => '100', 'height' => '100']],
            ],
        ],
    ]) ?>

</div>
