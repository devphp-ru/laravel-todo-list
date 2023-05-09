<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col" width="150">Изображение</th>
        <th scope="col">Дела</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody id="todo_lists">
    @if ($paginator->isNotEmpty())
        @foreach ($paginator as $value)
            <tr id="tr{{ $value->id }}">
                <th scope="row">{{ $value->id }}</th>
                <td width="15%" class="img">
                    <div class="block-images">
                        <div class="overlay" id="contenedor{{ $value->id }}">
                            <div class="overlay_container">
                                <a href="#close"><img src="{{ $value->getImage() }}" alt="..."></a>
                            </div>
                        </div>
                        <a href="#contenedor{{ $value->id }}">
                            <img src="{{ $value->getMiniImage() }}" id="image{{ $value->id }}" alt="...">
                        </a>
                    </div>
                </td>
                <td width="55%">
                    {{ $value->text }}
                    <br><small>создал: {{ $value->user->name }}</small>
                    <br><br>
                    @foreach ($value->tags as $tag)
                        <span class="t{{ $tag->id }}" style="border: 1px solid #ccc;padding:3px;">{{ $tag->name }}</span>
                        @if ($loop->iteration % 7 === 0)
                            <br><br>
                        @endif
                    @endforeach
                </td>
                <td>
                    @if (!$value->access()->where('user_id', $userId)->exists())
                    <button data-id="{{ $value->id }}" data-user-id="{{ $userId }}" type="button" class="btn btn-primary an_open-modal-edit" data-bs-toggle="modal" data-bs-target="#an_modal-todo">
                        Редактировать
                    </button>
                    <button data-id="{{ $value->id }}" type="button" class="btn btn-primary an_open-modal-access" data-bs-toggle="modal" data-bs-target="#an_modal-access">Доступ</button>
                    <button data-id="{{ $value->id }}" type="button" class="btn btn-danger an_remove-todo">Удалить</button>
                    @elseif ($value->access()->where('user_id', $userId)->exists() && $value->access->action === 2)
                        <button data-id="{{ $value->id }}" data-user-id="{{ $value->user->id }}" type="button" class="btn btn-primary an_open-modal-edit" data-bs-toggle="modal" data-bs-target="#an_modal-todo">
                            Редактировать
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<div class="pagination">
    @php $paginator->withPath('todo-lists') @endphp
    {{ $paginator->links('vendor.pagination.bootstrap-4') }}
</div>