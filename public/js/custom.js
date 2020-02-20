window.onload = function () {
    $('#btn-book-order').click(function (event) {
        var button = $(event.target); // Button that triggered the modal
        var book = button.data('whatever'); // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        var modal = $('#exampleModal');
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        modal.find('.modal-title').text("Заказать " + book);
    });

    setValuesForModelEdit();
    setValuesForBooks()

    $('#exampleModal').on('show.bs.modal', function (event) {


        //modal.find('.modal-body input').val(recipient)
    });


    if(location.href.indexOf('catalog-g') === -1)
        return;

    var inputs = document.querySelectorAll("input.form-control");
    var values = location.href.split('catalog-g/')[1]; //.split('/');

    if(values.indexOf('?') !== -1)
    {
        values = values.split('?')[0].split('/');
    }
    else
    {
        values = values.split('/');
    }

    for(var i = 0; i < inputs.length; i++)
    {
        if(typeof values[i] === 'string')
            inputs[i].value = decodeURI(values[i]);
    }
};

function setValuesForModelEdit() {
    $('.edit-object-btn').each(function (i, e) {

        $(e).click(function () {

            var button = $(this); // Button that triggered the modal
            var name = button.data('name'); // Extract info from data-* attributes
            var id = button.data('id'); // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            var modal = $('#exampleModalCenter');
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            modal.find('#object-name').val(name);
            modal.find('input[name="id"]').val(id);
        });
    });
}

function setValuesForBooks() {
    $('.btn-retrieve').each(function (i, e) {

        $(e).click(function () {
            var button = $(this); // Button that triggered the modal

            var id = button.data('id'); // Extract info from data-* attributes
            var form = $('#form-books')// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            $(form).find('input[name="id"]').val(id);

            // Вызов сделян прямо в этом событии, вместо атрибута onclick
            // в самом шаблоне, так как onclick срабатывает первым
            // а нам нужно наоборот
            objectAction('book', 'retrieve', successAjaxBook, '#form-books');
        });
    });
}


function orderBook() {

    var values = $('#order-book').serializeArray();

    $.ajax({
        url: '/ajax-c/catalog/ajaxOrder',
        method: 'POST',
        data: values,
        success: function (data) {
            $('.modal-backdrop').remove();
            modalHide($('#exampleModal'));
            $('body').removeClass('modal-open');

            setTimeout(200);

            $('#afterOrderBtn').click()
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function modalHide(obj) {
    obj.removeClass('show').css('display','none').removeAttr('aria-modal').attr('aria-hidden', true);
}



function deleteAjax(id, name, obj, callback) {

    var text = "Вы действительно хотите удалить '" + name + "'?";

    if(confirm(text))
    {
        var url = '/ajax-a/admin/ajaxDelete' + ucfirst(obj);

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                id
            },
            success: function (data) {
                data = JSON.parse(data);
                if(data.success)
                {
                    callback(data.html);
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

}

function objectAction(objectName, action, callback, selector = '#form') {

    var values = $(selector).serializeArray();

    var url = '/ajax-a/admin/ajax' + ucfirst(action) + ucfirst(objectName);

    $.ajax({
        url: url,
        method: 'POST',
        data: values,
        success: function (data) {
            data = JSON.parse(data);
            if(data.success)
            {
                callback(data.html, selector);
            }
        },
        error: function (data) {
            console.log(data);
        }
    });

}

function successAjaxBook(data)
{
    $('#object-table').html(data);
    setValuesForBooks();
}
function callbackAjaxSuccess(data, clearForm)
{
    $('#object-table').html(data);

    if(clearForm === '#form')
    {
        // очищаем поле ввода
        $('#inlineFormInputName').val('');
    }

    // привязываем новый обьект к вызову модели со своими данными
    setValuesForModelEdit();
}

function ucfirst(string) {
    string = string[0].toUpperCase() + string.substring(1);
    return string;
}

function searchBooks(e)
{
    var values = $('#form').serializeArray();

    $.ajax({
        url: '/ajax-c/catalog/ajaxSearchBooks',
        method: 'POST',
        data: values,
        success: function (data) {
            data = JSON.parse(data);
            if(data.success)
            {
                $('#book-catalog').html(data.html);
            }
        },
        error: function (data) {
            console.log(data);
        }
    });

    //location.href = "/catalog-g/" + values.join('/');
}