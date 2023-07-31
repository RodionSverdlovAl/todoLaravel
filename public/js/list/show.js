$(document).ready(function() {
    let csrf_token = $('input[name="_token"]').val();
    console.log('csrf-token = ', csrf_token);
    let tags = [];
    function hideAlerts() {
        $('#success-update-alert, #error-update-alert, #success-item-update-alert, #error-item-update-alert').fadeOut();
    }
    setTimeout(hideAlerts, 5000); // Automatically hide the alerts after 5 seconds (5000 milliseconds)


    $('.form-control').on('input focus', function() {
        const fieldId = $(this).attr('name');
        const errorId = '#' + fieldId + '-error';
        $(errorId).text(''); // Очистить текст ошибки
    });


    // Обработчик события ввода текста в поле тегов
    $('#tags').on('input', function() {
        let tagsInput = $('#tags').val();
        let newTags = tagsInput.split(' ').map(function(tag) {
            return tag.trim();
        });
        renderTags(newTags);
    });

    // Функция для отображения тегов
    function renderTags(newTags) {
        $('#tags-container').empty();
        tags = newTags;
        // Отображение каждого тега
        tags.forEach(function(tag) {
            let tagElement = $('<span class="badge badge-primary mr-2">#' + tag + '</span>');
            $('#tags-container').append(tagElement);
        });
    }


    $('#task-form').submit(function(event) {
        event.preventDefault();
        let route = $(this).data('route');
        let formData = new FormData();
        formData.append('list_id', $('#list_id').val())
        formData.append('title', $('#task-title').val());
        formData.append('description', $('#task-description').val());
        formData.append('photo', $('#task-photo')[0].files[0]);
        formData.append('status', $('#task-status').prop('checked') ? 'completed' : 'not completed');
        formData.append('tags', $('#tags').val());

        $.ajax({
            url: route,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                clearFileInput()
                $('#task-title').val('');
                $('#task-description').val('');
                $('#task-photo').val('');
                $('#task-status').prop('checked', false);
                $('#tags').val('');
                $('#tags-container').empty();
                $('.alert-success h5 span').html(response.message);
                $('.alert-success').fadeIn().delay(3000).fadeOut();
                $('#items-table').html(response.itemsTable);
                $('input[name="_token"]').val(csrf_token);
            },
            error: function(response) {
                if (typeof response.responseJSON.errors === 'string'){
                    $('.alert-danger h5 span').text(response.responseJSON.errors);
                    $('.alert-danger').removeClass('d-none').fadeIn().delay(3000).fadeOut();
                }else{
                    let errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key + '-error').text(value);
                    });
                }
            }
        });
    });
});



// Добавляем обработчик события для открытия исходного изображения при клике на превью
$('.thumbnail').forEach(function(thumbnail) {
    thumbnail.addEventListener('click', function() {
        let imageUrl = this.getAttribute('src');
        window.open(imageUrl, '_blank');
    });
});

function updateFileName() {
    $('#fileLabel').text($('#task-photo')[0].files[0].name);
}

function clearFileInput() {
    $('#task-photo').val('');
    $('#fileLabel').text('Выберите фотографию');
}


