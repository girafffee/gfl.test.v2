<?php if(empty($books)): ?>
    <div class="alert alert-info" role="alert">
        Не добавлено еще ни одной книги
    </div>
<?php else: ?>
    <h3 class="text-secondary d-flex justify-content-center">Доступные книги &nbsp;<a href="<?=route('create_book')?>"
                                                                                      style="text-decoration: none;" class="text-primary"><i class="fas fa-plus-circle"></i></a></h3>
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Название</th>
            <th scope="col">Дата создания</th>
            <th scope="col">Действие</th>
        </tr>
        </thead>
        <tbody>
        <?php $count=0; foreach($books as $book): ?>
            <?php if($book['status'] == BOOK_STATUS_DELETED){continue;}?>
            <tr>
                <th scope="row"><?=++$count?></th>
                <td><?=$book['title']?></td>
                <td><?=$book['created_at']?></td>
                <td>
                    <a href="<?=route('admin_single', [
                        'object'    => 'book',
                        'action'    => 'edit',
                        'id'        => $book['id']
                    ])?>"
                       title="Edit" class="text-success"><i class="fas fa-edit"></i></a>

                    <a href="javascript:void(0)" title="Delete" class="text-success"
                       onclick="deleteAjax(<?=$book['id']?>, '<?=$book['title']?>', 'book', successDeleteBook)">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if($count == 0): ?>
            <div class="alert alert-info" role="alert">
                Все книги удалены
            </div>
        <?php endif; ?>
        </tbody>
    </table>


    <h3 class="text-secondary d-flex justify-content-center">Удаленные книги &nbsp;<i class="text-danger fas fa-minus-circle"></i></h3>
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Название</th>
            <th scope="col">Дата создания</th>
            <th scope="col">Действие</th>
        </tr>
        </thead>
        <tbody>
        <?php $count=0; foreach($books as $book): ?>
            <?php if($book['status'] == BOOK_STATUS_ACTIVE){continue;}?>
            <tr>

                <th scope="row"><?=++$count?></th>
                <td><?=$book['title']?></td>
                <td><?=$book['created_at']?></td>
                <td>
                    <form action="" id="form">
                        <input type="hidden" name="id" value="<?=$book['id']?>">
                    </form>
                    <a href="javascript:void(0)" title="Retrieve" class="text-success"
                       onclick="objectAction('book', 'retrieve', successDeleteBook)">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>
