@extends('layouts.todo')
@section('content')
    <div class="card card-primary mt-2">
        <div class="card-header">
            <h3 class="card-title">Изменение фотографии пункта списка {{$item->title}}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form id="task-form" action="{{route('item.update.photo', $item->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')
            @if(isset($item->photo_path))
                <div class="m-3">
                    <a href="{{ asset($item->photo_path) }}" target="_blank">
                        <img id="preview-image" src="{{ asset($item->photo_path) }}" alt="Image" class="thumbnail">
                    </a>
                </div>
                    <div class="card-body">
                        @csrf
                        @method('patch')
                        <input type="number" class="form-control d-none" name="list_id" id="list_id" value="{{$item->list->id}}">
                        <div class="form-group">
                            <label for="task-photo">Прикрепить фотографию</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="photo" id="task-photo" onchange="updatePreviewImage()">
                                    <label class="custom-file-label" id="fileLabel" for="exampleInputFile">Выберите фотографию</label>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="clearFileInput()">Очистить</button>
                                </div>
                            </div>
                            @error('photo')
                            <p class="error-massage mt-1 text-danger" id="error-massage">{{$message}}</p>
                            @enderror
                            <p class="error-message text-danger" id="photo-error"></p>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Сохранить измененную фотографию</button>
                    </div>
            @endif
        </form>
    </div>
    <script src="{{asset('js/item/edit.js')}}"></script>
@endsection
