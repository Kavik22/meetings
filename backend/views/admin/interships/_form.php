<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\intership $intership */
/** @var app\models\Image $image */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="intership-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($intership, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($intership, 'link_to_ru_site')->textInput(['maxlength' => true]) ?>

    <?= $form->field($intership, 'annotation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($intership, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($intership, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($intership, 'date_start')->textInput() ?>

    <?= $form->field($intership, 'date_end')->textInput() ?>
    
    <!-- <?= $form->field($intership, 'country_id')->dropDownList(
        $countries,
        ['prompt' => 'Select Country'],
        ['value' => $intership->country_id]
    ) ?> -->

    <?= $form->field($intership, 'contact')->textInput(['maxlength' => true]) ?>

    
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
