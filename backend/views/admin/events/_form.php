<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Event $event */
/** @var app\models\Image $image */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($event, 'title')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($event, 'annotation')->textInput(['maxlength' => true]) ?> -->

    <?= $form->field($event, 'description')->textarea(['rows' => 6]) ?>

    <!-- <?= $form->field($event, 'location')->textInput(['maxlength' => true]) ?> -->

    <?= $form->field($event, 'address')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($event, 'contact')->textInput(['maxlength' => true]) ?> -->

    <?= $form->field($event, 'date_of_event')->textInput() ?>
    
    <?php if ($image->path): ?>
        <div class="form-group">
            <label>Current Image:</label>
            <div>
                <?= Html::img(\Yii::getAlias('@web') . '/' . $image->path, ['class' => 'img-thumbnail', 'style' => 'max-width: 200px;']) ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?= $form->field($image, 'imageFile')->fileInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
