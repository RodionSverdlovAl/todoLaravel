@extends('layouts.todo')
@section('content')
    <h1>Доступные мне списки</h1>

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
                    <th style="width: 20%">Создатель</th>
                    <th style="width: 20%">Мои права</th>
                    <th style="width: 20%" class="text-center">Кол-во задач</th>
                    <th style="width: 10%"></th>
                </tr>
                </thead>
                <tbody id="lists-table-body">
                @foreach($availableLists as $list)
                    <tr>
                        <td>{{$list->id}}</td>
                        <td><a>{{$list->name}}</a><br><small>{{$list->created_at}}</small></td>
                        <td>{{$list->getOwner->name}}</td>
                        <td>
                            @can('view', $list)
                                право на просмотр
                            @endcan
                            @can('update', $list)
                                право на редактирование
                            @endcan
                        </td>
                        <td class="project-state">
                            {{count($list->toDoItems)}} задач(а)
                        </td>
                        <td class="project-actions text-right">
                            @can('view', $list)
                                <a class="btn btn-primary btn-sm" href="{{route('list.show', $list->id)}}">
                                    <i class="fas fa-folder"></i>
                                </a>
                            @endcan
                            @can('update', $list)
                                <a class="btn btn-info btn-sm" href="{{route('list.edit', $list->id)}}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>

    <script src="{{asset('js/list/index.js')}}"></script>

@endsection
