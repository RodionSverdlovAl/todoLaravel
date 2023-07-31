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
