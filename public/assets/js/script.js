$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const currentUrl = document.location.protocol +"//"+ document.location.hostname + document.location.pathname;
    let apiPath = `/api/v1/todo`;
    const modalTodoName = `#an_modal-todo`;
    const inputText = `#form_todo #text`;
    const errorText = `#form_todo #an_input-error-text`;

    $(`#an_modal-todo`).on(`hidden.bs.modal`, function () {
        $(`#form_todo`).trigger('reset');
        $(`#form_todo input`).removeClass(`is-invalid`);
        $(`#form_todo .an_invalid-feedback`).empty();
        $(inputText).val('');
        $(`#form_todo #id`).val('');
        $(`#form_todo #file`).val('');
        $('.input-file-list-item').remove();
    });

    $(document).on(`click`, `.an_open-modal`, function () {
        getAllTags();
        const id = parseInt($(this).data(`id`));
        const userId = parseInt($(this).data(`user-id`));
        $(`${modalTodoName} .modal-title`).text(`Добавить`)
        $(`#form_todo #user_id`).val(userId);
        if (!isNaN(id) && (id > 0)) {
            $.ajax({
                url: `${apiPath}/show/${id}`,
                method: `GET`,
                dataType: `json`,
                cache: false,
                success: function (data) {
                    const item = data.item;
                    $(`#form_todo #id`).val(item.id);
                    $(`#form_todo #user_id`).val(item.user_id);
                    $(`#form_todo #text`).val(item.text);
                    let $select = $('#tags').selectize();
                    let control = $select[0].selectize;
                    $.each(item.tags, function (key, value) {
                        control.addItem(key, value);
                    });
                    control.refreshState();
                    if (item.mini_image) {
                        let new_file_input = '<div class="input-file-list-item">' +
                            '<img class="input-file-list-img" src="' + item.mini_image + '">' +
                            '<a data-id="' + item.id + '" href="javascript:" onclick="removeFilesItem(this); return false;" class="input-file-list-remove an_img-remove">x</a>' +
                            '</div>';
                        $(`#form_todo #images`).append(new_file_input);
                    }
                    $(`${modalTodoName} .modal-title`).text(`Редактировать`)
                }
            });
        }
    });

    $(document).on(`click`, `.an_img-remove`, function () {
        const id = parseInt($(this).data('id'));
        if (!isNaN(id) && (id > 0)) {
            $.ajax({
                url: `${apiPath}/delete-image/${id}`,
                method: `DELETE`,
                dataType: `json`,
                success: function (data) {
                    $(`#tr${id} .img`).html(``);
                },
                error: function () {
                    console.log('img-error>>>');
                },
            });
        }
    });

    $(`#an_save-todo`).on(`click`, function () {
        const id = parseInt($(`#form_todo #id`).val());
        const text = $(inputText).val().trim();
        if (text.length <= 0) {
            $(inputText).addClass(`is-invalid`);
            $(errorText).text('Заполните поле');
        } else {
            $(inputText).removeClass(`is-invalid`);
            $(errorText).text('');
            let data = new FormData($(`#form_todo`)[0]);
            let apiNewPath = `${apiPath}/create`;
            if (!isNaN(id) && (id > 0)) {
                apiNewPath = `${apiPath}/edit/${id}`;
            }
            $.ajax({
                url: apiNewPath,
                type: `POST`,
                contentType: false,
                processData: false,
                cache: false,
                data,
                success: function (data) {
                    $(`.todo-lists-body`).html(``);
                    $(inputText).val('');
                    $(`#form_todo #file`).val('');
                    $('.input-file-list-item').remove();
                    $(`#form_todo .selectize-input .item`).remove();
                    $(`.todo-lists-body`).html(data);
                    window.history.replaceState(null, null, currentUrl);
                    $(modalTodoName).modal(`hide`);
                },
                error: function (jqXHR) {
                    let responseText = jQuery.parseJSON(jqXHR.responseText);
                    let errors = responseText.errors;
                    for (let key in errors) {
                        $(`#${key}`).addClass(`is-invalid`);
                        $(`#an_input-error-${key}`).text(errors[key][0]);
                    }
                }
            });
        }
    });

    var dt = new DataTransfer();
    $('.input-file input[type=file]').on('change', function () {
        let $files_list = $(this).closest('.input-file').next();
        $files_list.empty();
        let file = this.files.item(0);
        dt.items.add(file);
        let reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function () {
            let new_file_input = '<div class="input-file-list-item">' +
                '<img class="input-file-list-img" src="' + reader.result + '">' +
                '<a href="javascript:" onclick="removeFilesItem(this); return false;" class="input-file-list-remove">x</a>' +
                '</div>';
            $files_list.append(new_file_input);
        }
        this.files = dt.files;
    });

    $(document).on(`click`, '.pagination a', function () {
        event.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchProductsData(page, currentUrl, currentUserId);
    });

    var $select = {};
    var control = {};
    if ($('select').is('.js-select-add')) {
        $('select.js-select-add').each(function (index) {
            var id = $(this).attr('id');
            $(this).removeClass('form-control');
            $select[id] = $('#' + id).selectize({
                persist: false,
                createOnBlur: false,
                create: true,
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                addTranslate: 'Добавить'
            });
            control[id] = $select[id][0].selectize;
        });
    }

    $(document).on(`click`, `.an_open-modal-access`, function () {
        const id = parseInt($(this).data(`id`));
        if (!isNaN(id) && (id > 0)) {
            $(`#form_user #todo_id`).val(id);
        }
    });

    $(document).on(`click`, `#an_save-access`, function () {
        let todoId = parseInt($(`#form_user #todo_id`).val());
        let userId = parseInt($(`#form_user #user_id`).val());
        let action = parseInt($(`#form_user #action`).val());
        $.ajax({
            url: `/api/v1/access/save`,
            method: `POST`,
            dataType: `json`,
            cache: false,
            data: {
                todo_list_id: todoId,
                user_id: userId,
                action: action,
            },
            success: function (data) {
                $(`#an_modal-access`).modal(`hide`);
            },
        });
    });

    $(document).on(`click`, `.an_remove-todo`, function () {
        const id = parseInt($(this).data('id'));
        if (!isNaN(id) && (id > 0)) {
            if (confirm('Удалить?')) {
                $.ajax({
                    url: `${apiPath}/delete/${id}`,
                    method: `DELETE`,
                    cache: false,
                    data: {
                        current_user_id: currentUserId,
                    },
                    success: function (data) {
                        $(`.todo-lists-body`).html(data);
                    },
                    error: function (jqXHR) {
                        let responseText = jQuery.parseJSON(jqXHR.responseText);
                        let errors = responseText.errors;
                        for (let key in errors) {
                            $(`#${key}`).addClass(`an-is-invalid`);
                            $(`.an_input-error-${key}`).text(errors[key][0]);
                        }
                    }
                });
            }
        }
    });
});
function getAllTags() {
    $.ajax({
        url: `/api/v1/todo/tags`,
        method: `GET`,
        dataType: `json`,
        change: false,
        success: function (data) {
            let $select = $('#tags').selectize();
            let control = $select[0].selectize;
            control.clear();
            control.clearOptions();
            let optionsList = [];
            $.each(data.tags, function (key, value) {
                optionsList.push({
                    id: key,
                    name: value
                });
            });
            control.addOption(optionsList);
            control.refreshState();
        }
    });
}
function removeFilesItem(target) {
    $(`#form_todo #file`).val('');
    $(target).closest('.input-file-list-item').remove();
}
function fetchProductsData(page, currentUrl, currentUserId) {
    const uri = document.location.pathname;
    $.ajax({
        url: `/api/v1/todo/pagination?page=${page}`,
        data: {
            uri: uri,
            current_user_id: currentUserId,
        },
        success: function (data) {
            const url = `${currentUrl}?page=${page}`;
            window.history.replaceState(null, null, url);
            $(`.todo-lists-body`).html(data);
        }
    });
}