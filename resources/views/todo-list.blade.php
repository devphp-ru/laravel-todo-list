<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        /* Список c превью */
        .input-file-list {
            padding: 10px 0;
        }
        .input-file-list-item {
            display: inline-block;
            margin: 0 15px 15px;
            width: 150px;
            vertical-align: top;
            position: relative;
        }
        .input-file-list-item img {
            width: 150px;
        }
        .input-file-list-name {
            text-align: center;
            display: block;
            font-size: 12px;
            text-overflow: ellipsis;
            overflow: hidden;
        }
        .input-file-list-remove {
            color: #000;
            text-decoration: none;
            display: inline-block;
            position: absolute;
            padding: 0;
            margin: 0;
            top: 5px;
            right: 5px;
            background: #e9ecef;
            width: 20px;
            height: 20px;
            text-align: center;
            line-height: 16px;
            border-radius: 50%;
        }
    </style>


</head>
<body>


<div class="container">

    <nav class="navbar bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('todo.index') }}">Список дел</a>
        </div>
    </nav>

    <div class="row">
        <div class="col-md-12">

            <button type="button" class="btn btn-primary mt-3 mb-2 an_open-modal" data-bs-toggle="modal" data-bs-target="#an_modal-todo">
                Добавить
            </button>

            <div class="card mt-3">
                <div class="card-body todo-lists-body">
                    @include('blocks._todo_lists')
                </div>
            </div>
            <div class="mt-3">

{{--                <form id="form_todo" enctype="multipart/form-data">--}}
{{--                    <input id="user_id" name="user_id" value="1" type="hidden">--}}
{{--                    <div class="mb-3">--}}
{{--                        <label for="text" class="form-label">Список дел</label>--}}
{{--                        <input id="text" name="text" value="" type="text" class="form-control ">--}}
{{--                        <div id="an_input-error-text" class="invalid-feedback"></div>--}}
{{--                    </div>--}}
{{--                    <div class="mb-3 input-file">--}}
{{--                        <input id="file" name="file" type="file" class="form-control" aria-label="file example" required>--}}
{{--                    </div>--}}
{{--                    <div class="input-file-list"></div>--}}
{{--                    <button id="an_save-todos" type="button" class="btn btn-primary">Добавить</button>--}}
{{--                </form>--}}
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
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
                <input id="user_id" name="user_id" value="1" type="hidden">
                <div class="mb-3">
                    <label for="text" class="form-label">Список дел</label>
                    <input id="text" name="text" value="" type="text" class="form-control ">
                    <div id="an_input-error-text" class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <div class="form-group">
                        <label for="tags-selectized">Теги</label>
                        <select id="tags" class="form-custom js-select-custom js-select-add" multiple name="tags[]">
                        @if ($tags->isNotEmpty())
                            @foreach ($tags as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        @endif
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <div id="my_tags"></div>
                </div>

                <div class="mb-3 input-file">
                    <input id="file" name="file" type="file" class="form-control" aria-label="file example" required>
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

<script src="{{ asset('/assets/js/jquery.min.js') }}"></script>

<link href="{{ asset('/assets/libs/selectize/selectize.default.css') }}" rel="stylesheet">
<script src="{{ asset('/assets/libs/selectize/selectize.js') }}"></script>

<script src="{{ asset('/assets/js/script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>