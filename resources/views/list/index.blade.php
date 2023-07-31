@extends('layouts.todo')
@section('content')
    <h1>Мои Дела</h1>
    <form class="form-horizontal bg-white mb-3" data-route="{{ route('list.create') }}">
        @csrf
        <div class="card-body">
            <h4>Создание нового списка дел</h4>
            <div class="form-group">
                <label for="list-name" class="col-sm-2 col-form-label">Название списка</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="list_name" placeholder="Введите название">
                    <p class="error-massage mt-1 text-danger" id="error-massage"></p>
                </div>
            </div>
            <div class="alert alert-success alert-dismissible d-none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5>
                    <i class="icon fas fa-check"></i>
                    <span></span>
                </h5>
            </div>

            <input type="number" class="form-control d-none" id="user_id" value="{{auth()->user()->id}}">
        </div>
        <!-- /.card-body -->
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-info">Добавить</button>
        </div>
        <!-- /.card-footer -->
    </form>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="error-alert" class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Мои списки дел</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects" id="lists-table">
                <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th style="width: 29%">Название</th>
                    <th style="width: 30%">Права доступа</th>
                    <th style="width: 30%" class="text-center">Кол-во дел</th>
                    <th style="width: 10%"></th>
                </tr>
                </thead>
                <tbody id="lists-table-body">
                @foreach($lists as $list)
                    <tr>
                        <td>{{$list->id}}</td>
                        <td><a>{{$list->name}}</a><br><small>{{$list->created_at}}</small></td>
                        <td>
                            <ul class="list-inline">
                                @foreach($list->sharedUsers as $sharedUser)
                                    <li>{{$sharedUser->name}} : {{$sharedUser->pivot->permission}}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="project-state">
                           {{count($list->toDoItems)}} задач(а)
                        </td>
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="{{route('list.show', $list->id)}}">
                                <i class="fas fa-folder"></i>
                            </a>
                            <a class="btn btn-info btn-sm" href="{{route('list.edit', $list->id)}}">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-danger btn-sm" href="#" onclick="event.preventDefault(); if (confirm('Вы уверены, что хотите удалить список?')) document.getElementById('delete-list-form-{{$list->id}}').submit();">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                        <form id="delete-list-form-{{$list->id}}" action="{{ route('list.delete', $list->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>

    <script src="{{asset('js/list/index.js')}}"></script>

@endsection
