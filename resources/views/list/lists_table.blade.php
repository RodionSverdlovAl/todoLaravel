{{--@foreach($lists as $list)--}}
{{--    <tr>--}}
{{--        <td>{{$list->id}}</td>--}}
{{--        <td><a>{{$list->name}}</a><br><small>{{$list->created_at}}</small></td>--}}
{{--        <td>--}}
{{--            <ul class="list-inline">--}}
{{--                <li class="list-inline-item">--}}
{{--                    <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png">--}}
{{--                </li>--}}
{{--                <li class="list-inline-item">--}}
{{--                    <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar3.png">--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </td>--}}
{{--        <td class="project-state">--}}
{{--            {{count($list->toDoItems)}} задач(а)--}}
{{--        </td>--}}
{{--        <td class="project-actions text-right">--}}
{{--            <a class="btn btn-primary btn-sm" href="{{route('list.show', $list->id)}}">--}}
{{--                <i class="fas fa-folder"></i>--}}
{{--            </a>--}}
{{--            <a class="btn btn-info btn-sm" href="#">--}}
{{--                <i class="fas fa-pencil-alt"></i>--}}
{{--            </a>--}}

{{--            <form id="delete-list-form-{{$list->id}}" action="{{ route('list.delete', $list->id) }}" method="POST" class="d-none">--}}
{{--                @csrf--}}
{{--                @method('DELETE')--}}
{{--            </form>--}}
{{--            <a class="btn btn-danger btn-sm" href="#" onclick="event.preventDefault(); if (confirm('Вы уверены, что хотите удалить список?')) document.getElementById('delete-list-form-{{$list->id}}').submit();">--}}
{{--                <i class="fas fa-trash"></i>--}}
{{--            </a>--}}
{{--        </td>--}}

{{--    </tr>--}}
{{--@endforeach--}}

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
            <form id="delete-list-form-{{$list->id}}" action="{{ route('list.delete', $list->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
            <a class="btn btn-danger btn-sm" href="#" onclick="event.preventDefault(); if (confirm('Вы уверены, что хотите удалить список?')) document.getElementById('delete-list-form-{{$list->id}}').submit();">
                <i class="fas fa-trash"></i>
            </a>
        </td>
    </tr>
@endforeach
