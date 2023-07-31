@extends('layouts.todo')
@section('content')
    <div class="card card-primary mt-2">
        <div class="card-header">
            <h3 class="card-title">Редактирование пункта списка {{$item->list->name}}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form id="task-form" action="{{route('item.update', $item->id)}}" method="post">
            @csrf
            @method('patch')
            <div class="card-body">
                @csrf
                <input type="number" class="form-control" name="list_id" id="list_id" value="{{$item->list->id}}" style="display: none">
                <div class="form-group">
                    <label for="task-title">Название</label>
                    <input type="text" class="form-control" name="title" id="task-title" value="{{$item->title}}">
                    @error('title')
                    <p class="error-massage mt-1 text-danger" id="error-massage">{{$message}}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="task-description">Описание</label>
                    <textarea type="text" class="form-control" name="description" id="task-description">{{$item->description}}</textarea>
                    @error('description')
                    <p class="error-massage mt-1 text-danger" id="error-massage">{{$message}}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tags">Теги</label>
                    <input type="text" class="form-control" id="tags" name="tags" value="@foreach($item->tags as $tag){{ $tag->name }} @endforeach">
                    <small class="form-text text-muted">Введите теги, разделяя их пробелами</small>
                    <div id="tags-container"></div>
                    @error('tags')
                    <p class="error-massage mt-1 text-danger" id="error-massage">{{$message}}</p>
                    @enderror
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="task-status" name="status">
                    <label class="form-check-label" for="task-status">Отметить как выполненную</label>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            </div>
        </form>
    </div>
    <script src="{{asset('js/item/edit.js')}}"></script>
@endsection
