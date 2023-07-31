$(document).ready(function() {
    let csrf_token = $('input[name="_token"]').val();
    console.log('csrf-token = ', csrf_token);

    function hideAlerts() {
        $('#success-alert, #error-alert').fadeOut();
    }

    // Automatically hide the alerts after 5 seconds (5000 milliseconds)
    setTimeout(hideAlerts, 5000);

    $('.form-horizontal').submit(function(event) {
        event.preventDefault();

        let formData = {
            'name': $('#list_name').val(),
            'user_id': $('#user_id').val()
        };

        let route = $(this).data('route');

        $.ajax({
            type: 'POST',
            url: route,
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#list_name').val(''); // чистим значение
                $('.alert-success h5 span').html(response.message);
                $('.alert-success').removeClass('d-none').fadeIn().delay(3000).fadeOut();
                $('#lists-table-body').html(response.listsTable);
                $('input[name="_token"]').val(csrf_token);
            },
            error: function(response) {
                if (typeof response.responseJSON.errors === 'string') {
                    $('#error-massage').text(response.responseJSON.errors);
                } else {
                    let errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#error-massage').text(value);
                    });
                }
            }
        });
    });
});

$('#list_name').on('input', function() {
    if ($(this).val() === '') {
        $('#error-massage').text(''); // Очистить текст ошибки
    }
});
