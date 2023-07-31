@extends('layouts.todo')
@section('content')
    <h1>Редактирование списка {{$list->name}}</h1>
    <form class="form-horizontal bg-white mb-3" action="{{ route('list.update', $list->id) }}" method="post">
        @csrf
        @method('patch')
        <div class="card-body">
            <div class="form-group">
                <label for="list-name" class="col-sm-2 col-form-label">Название списка</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="list_name" placeholder="Введите название" value="{{$list->name}}">
                    @error('name')
                    <p class="error-massage mt-1 text-danger" id="error-massage">{{$message}}</p>
                    @enderror
                </div>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-info">Сохранить изменения</button>
        </div>
        <!-- /.card-footer -->
    </form>

    @can('grantAccess', $list)
    <h1>Выдать права доступа </h1>
    <form action="{{ route('list.share', $list->id) }}" method="POST" class="mb-3">
        @csrf
        <div class="form-group">
            <label for="user_id">Выберите пользователя:</label>
            <select name="user_id" id="user_id" class="form-control">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="permission">Выберите права доступа:</label>
            <select name="permission" id="permission" class="form-control">
                <option value="read">Чтение</option>
                <option value="edit">Чтение и редактирование</option>
            </select>
        </div>
        <button class="btn btn-success" type="submit">Выдать права</button>
    </form>

    <h4>Пользователи которые уже имеют права на этот список</h4>
    <ul class="list-inline">
        @foreach($list->sharedUsers as $sharedUser)
            <li>Пользователь: {{$sharedUser->name}} : {{$sharedUser->pivot->permission}}</li>
        @endforeach
    </ul>
    @endcan


    <script>
        // Когда пользователь начинает вводить в поле, очищаем ошибку
        $('#list_name').on('input', function() {
            $('#error-massage').text('');
        });
    </script>
@endsection
