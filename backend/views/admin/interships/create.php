<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\intership $model */

$this->title = 'Create intership';
?>

<div class="intership-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'intership' => $intership,  
        'countries' => $countries,
        'image' => $image,
    ]) ?>

</div>
