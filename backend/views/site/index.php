<?php

/** @var yii\web\View $this */

$this->title = 'AIESEC';
?>
<div class="site-index">
    <section class="mb-4">
        <h3>First photo</h3>
    </section>
    <section class="mb-4">
        <h3>Events</h3>
        <p>We do this because...</p>
        <div class="row">
            <h4>Upcoming</h4>
            <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="col-md-4 mb-2">
                <div class="card">
                    <h5>Event <?= $i?></h5>
                    <p>some description</p>
                    <a href="btn btn-primary">more details</a>
                </div>
            </div>
            <?php endfor; ?>
            <h4>Past</h4>
            <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="col-md-4">
                <div class="card">
                    <h5>Event <?= $i?></h5>
                    <p>some description</p>
                    <a href="btn btn-primary">more details</a>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </section>
    <section class="mb-4">
        <?php foreach (['Russia', 'Tunisia', 'Sri Lanka', 'Turkey'] as $place): ?>
        <h3>Interships in <?= $place ?></h3>
        <p>We do this because...</p>
        <div class="row">
            <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="col-md-4">
                <div class="card">
                    <h5>Intership <?= $i?></h5>
                    <p>some description</p>
                    <a href="btn btn-primary">more details</a>
                </div>
            </div>
            <?php endfor; ?>
        </div>
        <?php endforeach; ?>
    </section>
</div>