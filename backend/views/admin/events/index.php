<?php
use yii\helpers\Url;

/** @var yii\web\View $this */
$this->title = 'Admin panel';

?>
<div class="site-index">
    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message):
        $parts = explode("_", $type);
        $alertType = array_pop($parts) == 'success' ? 'success' : 'danger';
        ?>
        <div class="alert alert-<?= $alertType ?>">
            <?= $message ?>
        </div>
    <?php endforeach; ?>
    <section class="mb-4">
        <?php if ($status == 'upcoming'): ?>
            <h1 class="mb-3">
                Upcoming events
            </h1>
            <a href="<?= Url::toRoute(['admin/events/create']) ?>" class="btn btn-success">Create</a>
            <a href="<?= Url::toRoute(['admin/events/past']) ?>" class="btn btn-secondary">Past events</a>
        <?php else: ?>
            <h1 class="mb-3">
                Past events
            </h1>
            <a href="<?= Url::toRoute(['admin/events/create']) ?>" class="btn btn-success">Create</a>
            <a href="<?= Url::toRoute(['admin/events/upcoming']) ?>" class="btn btn-primary">Upcoming events</a>
        <?php endif; ?>

    </section>
    <section>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">
                        Title
                    </th>
                    <th scope="col">
                        Address
                    </th>
                    <th scope="col">
                        Количество участников
                    </th>
                    <th scope="col">
                        Date of event
                    </th>
                </tr>

            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>

                    <tr>
                        <th scope="row">
                            <?= $event->title ?>
                        </th>
                        <td scope="row">
                            <?= $event->address ?>
                        </td>
                        <td scope="row">
                            <?= count($event->participants) ?>
                        </td>
                        <td scope="row">
                            <?= $event->date_of_event ?>
                        </td>
                        <td>
                            <a href="<?= Url::toRoute(['admin/events/' . $event->id]) ?>">View</a>
                            <a href="<?= Url::toRoute(['admin/events/update/' . $event->id]) ?>">Edit</a>
                            <a href="<?= Url::toRoute(['admin/events/delete/' . $event->id]) ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach ?>

            </tbody>
        </table>
    </section>
</div>