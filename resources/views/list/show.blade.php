@extends('layouts.todo')
@section('content')
<h1>Список: {{$list->name}}</h1>

@if(session('success-update'))
    <div id="success-update-alert" class="alert alert-success">
        {{ session('success-update') }}
    </div>
@endif

@if(session('error-update'))
    <div id="error-update-alert" class="alert alert-danger">
        {{ session('error-update') }}
    </div>
@endif

@if(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif

<div class="card card-primary">

    <!-- /.card-header -->
    <!-- form start -->
    @if (auth()->user()->can('createItem', $list))
        <div class="card-header">
            <h3 class="card-title">Добавление пункта списка</h3>
        </div>
        <form id="task-form" data-route="{{ route('item.create') }}">
            <div class="card-body">
                @csrf
                <input type="number" class="form-control d-none" name="list_id" id="list_id" value="{{$list->id}}">
                <div class="form-group">
                    <label for="task-title">Название</label>
                    <input type="text" class="form-control" name="title" id="task-title" placeholder="Введите название задачи">
                    <p class="error-message text-danger" id="title-error"></p>
                </div>
                <div class="form-group">
                    <label for="task-description">Описание</label>
                    <textarea type="text" class="form-control" name="description" id="task-description" placeholder="Введите описание задачи"></textarea>
                    <p class="error-message text-danger" id="description-error"></p>
                </div>

                <div class="form-group">
                    <label for="tags">Теги</label>
                    <input type="text" class="form-control" id="tags" name="tags" placeholder="Введите теги">
                    <small class="form-text text-muted">Введите теги, разделяя их пробелами</small>
                    <div id="tags-container"></div>
                </div>
                <div class="form-group">
                    <label for="task-photo">Прикрепить фотографию</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="photo" id="task-photo" onchange="updateFileName()">
                            <label class="custom-file-label" id="fileLabel" for="exampleInputFile">Выберите фотографию</label>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="clearFileInput()">Очистить</button>
                        </div>

                    </div>
                    <p class="error-message text-danger" id="photo-error"></p>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="task-status" name="status">
                    <label class="form-check-label" for="task-status">Отметить как выполненную</label>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Создать задачу</button>
            </div>
        </form>
    @endif

</div>

<form action="{{ route('items.filterByTags', $list->id) }}" method="GET">
    @csrf
    <div class="form-group">
        <h3>Фильтрация по тегам</h3>
        <label for="tags">Введите теги через пробел:</label>
        <input type="text" class="form-control" name="tags" id="tags" value="{{ request('tags') }}">
    </div>
    <button type="submit" class="btn btn-primary">Фильтровать</button>
</form>

<div class="row">
    <div class="col-md-8 mt-3 mb-3">
        <form action="{{ route('items.search', $list->id) }}" method="GET">
            @csrf
            <h3>Поиск элеменов списка</h3>
            <div class="input-group">
                <input type="number" class="form-control d-none" name="list_id" id="list_id" value="{{$list->id}}">
                <input type="search" class="form-control form-control-lg" name="title" placeholder="Введите название задачи">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-lg btn-default">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
        <a class="btn btn-default mt-2" href="{{route("list.show",$list->id)}}">Сбросить поиск</a>
    </div>
</div>

@if(session('success-item-update'))
    <div id="success-item-update-alert" class="alert alert-success">
        {{ session('success-item-update') }}
    </div>
@endif

@if(session('error-item-update'))
    <div id="error-item-update-alert" class="alert alert-danger">
        {{ session('error-item-update') }}
    </div>
@endif

<div class="alert alert-success alert-dismissible m-3" style="display: none">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> <span></span></h5>
</div>

<div class="alert alert-danger alert-dismissible d-none">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-ban"></i> <span>Field</span></h5>
</div>

<section class="content pb-3">
    <h3>Задачи</h3>
    <div class="container-fluid h-100" id="items-table" style="display: flex">
        <div class="card card-row card-default w-50 mr-2">
            <div class="card-header bg-info">
                <h3 class="card-title">
                   В работе
                </h3>
            </div>
                <div class="card-body">
                    @foreach($itemsNotCompleted as $item)
                    <div class="card card-light card-outline">
                        <div class="card-header">
                            <h5 class="card-title">{{$item->title}}</h5>
                            <div class="card-tools">
                                <a href="#" class="btn btn-tool btn-link">#2</a>
                                <a href="{{route('item.edit', $item->id)}}" class="btn btn-tool">
                                    <i class="fas fa-pen"></i> редактировать
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>
                               {{$item->description}}
                            </p>
                            @if(isset($item->photo_path))
                                <div>
                                    <a href="{{asset($item->photo_path)}}" target="_blank">
                                        <img src="{{asset($item->photo_path)}}" alt="Image" class="thumbnail">
                                    </a>
                                </div>
                                <div>
                                    <a href="{{route('item.edit.photo', $item->id)}}" class="btn btn-tool">
                                        <i class="fas fa-pen"></i>
                                        изменить фото
                                    </a>
                                </div>
                            @else
                                <div>
                                    <a href="{{route('item.add.photo', $item->id)}}" class="btn btn-tool">
                                        <i class="nav-icon far fa-plus-square"></i>
                                        прикрепить фотографию
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div id="tags-container" class="m-3">
                            @foreach($item->tags as $tag)
                                <span class="badge badge-primary mr-2">#{{$tag->name}}</span>
                            @endforeach
                        </div>
                        <form class="m-3" method="POST" action="{{ route('item.complete', $item->id) }}">
                            @csrf
                            @method('PUT')
                            <!-- Ваш код полей формы -->
                            <button class="btn btn-success" type="submit">Отметить как выполненную</button>
                        </form>
                        <form class="m-3" method="POST" action="{{ route('item.delete', $item->id) }}">
                            @csrf
                            @method('DELETE')
                            <!-- Ваш код полей формы -->
                            <button class="btn btn-danger" type="submit">Удалить</button>
                        </form>


                    </div>
                    @endforeach
                </div>
        </div>

        <div class="card card-row card-success w-50 ml-2">
            <div class="card-header">
                <h3 class="card-title">
                    Выполненные
                </h3>
            </div>
                <div class="card-body">
                    @foreach($itemsCompleted as $item)
                        <div class="card card-light card-outline">
                            <div class="card-header">
                                <h5 class="card-title">{{$item->title}}</h5>
                                <div class="card-tools">
                                    <a href="#" class="btn btn-tool btn-link">#2</a>
                                    <a href="{{route('item.edit', $item->id)}}" class="btn btn-tool">
                                        <i class="fas fa-pen"></i>
                                        Редактировать
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>
                                    {{$item->description}}
                                </p>
                                @if(isset($item->photo_path))
                                    <div>
                                        <a href="{{asset($item->photo_path)}}" target="_blank">
                                            <img src="{{asset($item->photo_path)}}" alt="Image" class="thumbnail">
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{route('item.edit.photo', $item->id)}}" class="btn btn-tool">
                                            <i class="fas fa-pen"></i>
                                           изменить фото
                                        </a>
                                    </div>
                                @else
                                    <div>
                                        <a href="{{route('item.add.photo', $item->id)}}" class="btn btn-tool">
                                            <i class="nav-icon far fa-plus-square"></i>
                                            прикрепить фотографию
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div id="tags-container" class="m-2">
                                @foreach($item->tags as $tag)
                                    <span class="badge badge-primary mr-2">#{{$tag->name}}</span>
                                @endforeach
                            </div>
                            <form class="m-3" method="POST" action="{{ route('item.delete', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <!-- Ваш код полей формы -->
                                <button class="btn btn-danger" type="submit">Удалить</button>
                            </form>


                        </div>
                    @endforeach
                </div>
        </div>
    </div>
</section>

<script src = "{{asset('js/list/show.js')}}"></script>
@endsection
