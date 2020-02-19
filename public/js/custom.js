window.onload = function () {
    $('#btn-book-order').click(function (event) {
        var button = $(event.target); // Button that triggered the modal
        var book = button.data('whatever'); // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        var modal = $('#exampleModal');
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        modal.find('.modal-title').text("Заказать " + book);
    });

    $('.edit-author-btn').each(function (i, e) {

        $(e).click(function () {
            var button = $(this); // Button that triggered the modal
            var name = button.data('name'); // Extract info from data-* attributes
            var id = button.data('id'); // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            var modal = $('#exampleModalCenter');
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            modal.find('#author-name').val(name);
            modal.find('input[name="id"]').val(id);
        });
    });


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
function orderBook(book_info) {

    var data = {};
    var inputs = document.querySelectorAll("input.form-control");
    for(let i = 0; i < inputs.length; i++)
    {
        data[inputs[i].name] = inputs[i].value;
    }


    $.ajax({
        url: '/ajax/ajaxOrder',
        method: 'POST',
        data: {
            book: book_info,
            inp: data
        },
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
            if(data.length > 0)
            {
                callback(data, selector);
            }
        },
        error: function (data) {
            console.log(data);
        }
    });

}

function successDeleteBook(data)
{
    $('#admin-table').html(data);
}
function callbackAjaxSuccess(data, clearForm)
{
    if(clearForm === '#form')
        $('#inlineFormInputName').val('');
    else
    {
        $('.edit-author-btn').each(function (i, e) {
            $(e).click(function () {
                var button = $(this); // Button that triggered the modal
                var name = button.data('name'); // Extract info from data-* attributes
                var id = button.data('id'); // Extract info from data-* attributes
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                var modal = $('#exampleModalCenter');
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                modal.find('#author-name').val(name);
                modal.find('input[name="id"]').val(id);
            });
        });
    }


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