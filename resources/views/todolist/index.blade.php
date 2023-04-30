@extends('layouts.default')
@section('title', 'Список дел')
@section('content')
    <div class="row">
        <div class="col-md-12">

            <button data-user-id="{{ $userId }}" type="button" class="btn btn-primary mt-3 mb-2 an_open-modal" data-bs-toggle="modal" data-bs-target="#an_modal-todo">
                Добавить
            </button>

            <form class="d-flex" role="search">
                <input id="query" name="query" class="form-control me-2" type="search" placeholder="Поиск..." aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Поиск</button>
            </form>
            @if (Request::has('query'))
                <div class="col-auto">
                    <a href="{{ route('todo.index') }}" class="btn btn-danger btn-sm mb-2 btn-width" title="Сбросить фильтр">Сбросить</a>
                </div>
            @endif

            <div class="card mt-3">
                <div class="card-body todo-lists-body">
                    @include('todolist.blocks._todo_lists')
                </div>
            </div>
        </div>
    </div>

    <!-- Modal добавление/редактирование -->
    <div class="modal fade" id="an_modal-todo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Добавить</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_todo" enctype="multipart/form-data">
                    <div class="modal-body modal-fields">
                        <input id="id" name="id" value="" type="hidden">
                        <input id="user_id" name="user_id" value="" type="hidden">
                        <input id="current_user_id" name="current_user_id" value="{{ Auth::user()->id }}" type="hidden">
                        <div class="mb-3">
                            <label for="text" class="form-label">Список дел</label>
                            <input id="text" name="text" value="" type="text" class="form-control ">
                            <div id="an_input-error-text" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="tags-selectized">Теги</label>
                                <select id="tags" class="form-custom js-select-custom js-select-add" multiple name="tags[]"></select>
                            </div>
                        </div>
                        <div class="mb-3 input-file">
                            <input id="file" name="file" type="file" class="form-control" aria-label="file example" required>
                            <div id="an_input-error-file" class="invalid-feedback"></div>
                        </div>
                        <div class="input-file-list"></div>
                        <div id="images"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button id="an_save-todo" type="button" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal доступы другим пользователям -->
    <div class="modal fade" id="an_modal-access" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Доступ к делу</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="form_user">
                <div class="modal-body">
                        <input id="todo_id" name="todo_id" value="" type="hidden">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Пользователи</label>
                            <select id="user_id" name="user_id" class="form-select" aria-label="Default select example">
                                @foreach ($users as $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="action" class="form-label">Доступ</label>
                            <select id="action" name="action" class="form-select" aria-label="Default select example">
                                <option value="0">Запретить</option>
                                <option value="1">Чтение</option>
                                <option value="2">Чтение/Редактирование</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button id="an_save-access" type="button" class="btn btn-primary">Сохранить</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection