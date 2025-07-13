<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\intership $intership */
/** @var app\models\Image $image */

$this->title = 'Update intership: ' . $intership->title;
$this->params['breadcrumbs'][] = ['label' => 'interships', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $intership->title, 'url' => ['view', 'id' => $intership->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="intership-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'intership' => $intership,
        'image' => $image,
        'countries' => $countries,
    ]) ?>

</div>
