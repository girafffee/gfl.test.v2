<div class="alert alert-success" role="alert">
    <h3 class="alert-heading">Заказ от <?=$customer['email']?></h3>
    <p><b><?=$customer['fio']?></b> совершил заказ книги <b>"<?=$book['title']?>"</b> в колличестве <b><?=$customer['count']?> штук</b>
    </p>
    <p><h3>Краткое описание книги: </h3>
    <?=$book['desc_short']?></p>
    <hr>
    <p class="mb-0">Вернуться на <a href="<?=SITE_URL?>">GFL.TEST</a>.</p>
</div>
