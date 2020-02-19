<?php if(empty($books)): ?>
    <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
            По данному запросу ничего не найдено
        </div>
    </div>
<?php else: ?>
    <?php foreach ($books as $book) : ?>

        <div class="col mb-4">
            <div class="card">
                <img src="public/img/default.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title text-success"><b>
                            <a class="text-success"
                               href="<?=route('single_book', ['view', $book['id']])?>"><?=$book['title']?></a>
                        </b></h5>
                    <p class="card-text"><b>Жанры</b>: <?=$book['genres']?></p>
                    <p class="card-text"><b>Авторы</b>: <?=$book['authors']?></p>
                    <p class="card-text"><b>Описание</b>: <?=$book['desc_short']?></p>

                    <a href="<?=route('single_book', ['view', $book['id']])?>"
                       class="btn btn-success">Подробнее...</a>

                </div>
            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>