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

function updatePreviewImage() {
    let inputElement = document.getElementById('task-photo');
    let fileLabelElement = document.getElementById('fileLabel');
    let previewImageElement = document.getElementById('preview-image');

    let fileName = inputElement.files[0].name;
    fileLabelElement.innerHTML = fileName;

    if (inputElement.files && inputElement.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            previewImageElement.setAttribute('src', e.target.result);
        }

        reader.readAsDataURL(inputElement.files[0]);
    }
}

function clearFileInput() {
    let inputElement = document.getElementById('task-photo');
    let fileLabelElement = document.getElementById('fileLabel');
    let previewImageElement = document.getElementById('preview-image');

    inputElement.value = ''; // Очищаем значение инпута
    fileLabelElement.innerHTML = 'Выберите фотографию';
    previewImageElement.setAttribute('src', "{{asset($item->photo_path)}}"); // Возвращаем старое изображение
}
