<?php
use yii\helpers\Url;

/** @var yii\web\View $this */
$this->title = 'Admin panel';

?>
<div class="site-index">
    <section class="mb-4">
    <?php if ($status == 'upcoming'): ?>
            <h1 class="mb-3">
                Upcoming interships
            </h1>
            <a href="<?= Url::toRoute(['admin/interships/create'])?>" class="btn btn-success">Create</a>
            <a href="<?= Url::toRoute(['admin/interships/past'])?>" class="btn btn-secondary">Past interships</a>
        <?php else: ?>
            <h1 class="mb-3">
                Past interships
            </h1>
            <a href="<?= Url::toRoute(['admin/interships/create'])?>" class="btn btn-success">Create</a>
            <a href="<?= Url::toRoute(['admin/interships/upcoming'])?>" class="btn btn-primary">Upcoming interships</a>
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
                        Price
                    </th>
                    <th scope="col">
                        Date start
                    </th>
                    <th scope="col">
                        Date end
                    </th>
                    <th scope="col">
                        Contact
                    </th>
                </tr>

            </thead>
            <tbody>
                <?php foreach ($interships as $intership): ?>

                <tr>
                    <th scope="row">
                        <?= $intership->title?>
                    </th>
                    <td scope="row">
                        <?= $intership->price?>
                    </td>
                    <td scope="row">
                        <?= $intership->date_start?>
                    </td>
                    <td scope="row">
                        <?= $intership->date_end?>
                    </td>
                    <td scope="row">
                        <?= $intership->contact?>
                    </td>
                    <td>
                        <a href="<?= Url::toRoute(['admin/interships/' . $intership->id])?>">View</a>
                        <a href="<?= Url::toRoute(['admin/interships/update/' . $intership->id])?>">Edit</a>
                        <a href="<?= Url::toRoute(['admin/interships/delete/' . $intership->id])?>">Delete</a>
                    </td>
                </tr>
                <?php endforeach?>

            </tbody>
        </table>
    </section>
</div>