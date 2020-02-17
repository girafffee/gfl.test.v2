function ucfirst(string) {
    string = string[0].toUpperCase() + string.substring(1);
    return string;
}

function searchBooks(e)
{
    var values = $('#form').serializeArray();

    $.ajax({
        url: '/ajax/ajaxSearchBooks',
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