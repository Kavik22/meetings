<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Event $event */
/** @var app\models\Image $image */

$this->title = 'Update Event: ' . $event->title;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $event->title, 'url' => ['view', 'id' => $event->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'event' => $event,
        'image' => $image,
    ]) ?>

</div>
