$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    function displayMessage(message, bgcolor = `light`) {
        new Toast({
            title: false,
            text: message,
            theme: bgcolor,
            autohide: true,
            interval: 3000
        });
    }

    function getSelectize() {
        let $select = $(`#tags`).selectize();
        return $select[0].selectize;
    }

    const modalNameTodo = `#an_modal-todo`;
    const formNameTodo = `#an_form-todo`;

    $(modalNameTodo).on(`hidden.bs.modal`, function () {
        const control = getSelectize();
        control.clear();
        control.clearOptions();
        control.refreshState();
        $(formNameTodo).trigger(`reset`);
        $(`${formNameTodo} #id, #user_id`).val(``);
        $(`${formNameTodo} #text`).removeClass(`is-invalid`);
        $(`${modalNameTodo} .modal-title`).text(``);
        $(`.input-file-list-item`).remove();
    });

    let $select = {};
    let control = {};
    if ($(`select`).is(`.js-select-add`)) {
        $('select.js-select-add').each(function (index) {
            let id = $(this).attr(`id`);
            $(this).removeClass(`form-control`);
            $select[id] = $(`#${id}`).selectize({
                persist: true,
                createOnBlur: false,
                create: true,
                valueField: `id`,
                labelField: `name`,
                searchField: `name`,
                addTranslate: `Добавить`
            });
            control[id] = $select[id][0].selectize;
        });
    }

    function getAllTags() {
        $(`#an_spinner-tags`).html(`Теги <span class="spinner-border spinner-border-sm an_spinner-tags-process" role="status"></span>`);
        const userId = parseInt($(`${formNameTodo} #current_user_id`).val());
        $.ajax({
            url: `/api/v1/tags/for-select?user_id=${userId}`,
            method: `GET`,
            dataType: `json`,
            cache: false,
            success: function (data) {
                let optionsList = [];
                $.each(data.tags, function (key, value) {
                    optionsList.push({
                        id: key,
                        name: value
                    });
                });
                let control = getSelectize();
                control.addOption(optionsList);
                control.refreshState();
                $(`.an_spinner-tags-process`).remove();
            }
        });
    }

    $(`.input-file input[type=file]`).on(`change`, function () {
        let dt = new DataTransfer();
        let files_list = $(this).closest(`.input-file`).next();
        files_list.empty();
        let file = this.files.item(0);
        dt.items.add(file);
        let reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function () {
            files_list.append(`<div class="input-file-list-item">
                <img class="input-file-list-img" src="${reader.result}" alt="...">
                <a href="javascript:" class="input-file-list-remove">x</a>
            </div>`);
        }
        this.files = dt.files;
    });

    function showLoadProcess(el) {
        el.prop(`disabled`, true);
        el.html(`<span class="spinner-border spinner-border-sm spinner-load-process" role="status"></span> ${el.html()}`);
    }

    function hideLoadProcess(el) {
        el.prop(`disabled`, false);
        $(`.spinner-load-process`).remove();
    }

    $(`.an_open-modal-create`).on(`click`, function () {
        $(`#an_save-todo`).prop(`disabled`, true);
        getAllTags();
        let this_ = $(this);
        showLoadProcess(this_);
        const userId = parseInt($(this_).data(`user-id`));
        $(`${formNameTodo} #user_id`).val(userId);
        $(`${modalNameTodo} .modal-title`).text(`Добавить`);
        setTimeout(function () {
            hideLoadProcess(this_);
            $(`#an_save-todo`).prop(`disabled`, false);
        }, 2000);
    });

    let apiPath = `/api/v1/todo`;

    $(document).on(`click`, `.an_open-modal-edit`, function () {
        $(`#an_save-todo`).prop(`disabled`, true);
        getAllTags();
        let this_ = $(this);
        showLoadProcess(this_);
        const id = parseInt($(this_).data('id'));
        const userId = parseInt($(this_).data(`user-id`));
        $(`${formNameTodo} #user_id`).val(userId);
        if (!isNaN(id) && (id > 0)) {
            $.ajax({
                url: `${apiPath}/show/${id}`,
                method: `GET`,
                dataType: `json`,
                cache: false,
                success: function (data) {
                    const item = data.item;
                    $(`${modalNameTodo} .modal-title`).text(`Редактировать`);
                    let control = getSelectize();
                    $.each(item.tags, function (key, value) {
                        control.addItem(key, value);
                    });
                    control.refreshState();
                    $(`${formNameTodo} #id`).val(item.id);
                    $(`${formNameTodo} #user_id`).val(item.user_id);
                    $(`${formNameTodo} #text`).val(item.text);
                    if (item.mini_image) {
                        $(`${formNameTodo} #images`).append(`<div class="input-file-list-item">
                            <img class="input-file-list-img" src="${item.mini_image}" alt="...">
                            <a data-id="${item.id}" class="input-file-list-remove">x</a>
                        </div>`);
                    }
                    hideLoadProcess(this_);
                    $(`#an_save-todo`).prop(`disabled`, false);
                }
            });
        }
    });

    $(document).on(`click`, `.input-file-list-remove`, function () {
        $(`${formNameTodo} #file`).val(``);
        let this_ = $(this);
        $(this_).closest(`.input-file-list-item`).remove();
        const id = parseInt($(this_).data(`id`));
        if (!isNaN(id) && (id > 0) && confirm(`Удалить?`)) {
            $.ajax({
                url: `${apiPath}/delete-image/${id}`,
                method: `DELETE`,
                dataType: `json`,
                cache: false,
                success: function (data) {
                    if (!data.status) {
                        displayMessage(`Ошибка удаления.`);
                    } else {
                        displayMessage(`Изображение удалено.`);
                        $(`#tr${id} .img`).html(``);
                    }
                }
            });
        }
    });

    function getUserTags(userId) {
        $.ajax({
            url: `/api/v1/tags/for-filter?user_id=${userId}`,
            method: `GET`,
            dataType: `json`,
            cache: false,
            success: function (data) {
                const items = data.tags;
                let str = '';
                $.each(items, function (key, value) {
                    str += `<span data-id="${key}" class="an_tag-link tag-click">${value}</span> `;
                });
                $(`#an_tags-links`).html(str);
            }
        });
    }

    const currentUrl = document.location.protocol + '//' + document.location.hostname + document.location.pathname;

    $(`#an_save-todo`).on(`click`, function () {
        const id = parseInt($(`${formNameTodo} #id`).val());
        const text = $(`${formNameTodo} #text`).val().trim();
        if (text.length <= 0) {
            $(`${formNameTodo} #text`).addClass(`is-invalid`);
        } else {
            let data = new FormData($(formNameTodo)[0]);
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
                    getUserTags(currentUserId);
                    $(`.todo-lists-body`).html(data);
                    window.history.replaceState(null, null, currentUrl);
                    $(modalNameTodo).modal(`hide`);
                }
            });
        }
    });

    $(document).on(`click`, `.an_remove-todo`, function () {
        const id = parseInt($(this).data(`id`));
        if (!isNaN(id) && (id > 0) && confirm(`Удалить?`)) {
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
                        $(`#${key}`).addClass(`is-invalid`);
                        $(`.an_input-error-${key}`).text(errors[key][0]);
                    }
                }
            });
        }
    });

    const formNameUser = `#an_form-user`;

    $(`#an_modal-access`).on(`hidden.bs.modal`, function () {
        $(`${formNameUser} #user_id`).removeClass(`is-invalid`);
        $(`${formNameUser} #todo_id`).val(``);
        $(`#an_access-lists`).html(``);
    });

    $(document).on(`click`, `.an_open-modal-access`, function () {
        const todoId = parseInt($(this).data(`id`));
        if (!isNaN(todoId) && (todoId > 0)) {
            $.ajax({
                url: `/api/v1/access/${todoId}`,
                method: `GET`,
                dataType: `json`,
                cache: false,
                success: function (data) {
                    const items = data.items;
                    let str = '';
                    for (let i = 0; i < items.length; i++) {
                        str += `<small>${items[i].user.name}</small>, `;
                    }
                    $(`#an_access-lists`).html(str.slice(0, -2));
                    $(`${formNameUser} #todo_id`).val(todoId);
                }
            });
        }
    });

    $(document).on(`click`, `#an_save-access`, function () {
        let todoId = parseInt($(`${formNameUser} #todo_id`).val());
        let userId = parseInt($(`${formNameUser} #user_id`).val());
        let action = parseInt($(`${formNameUser} #action`).val());
        if (isNaN(userId)) {
            $(`${formNameUser} #user_id`).addClass(`is-invalid`);
        }
        if (!isNaN(todoId) && (todoId > 0) && (!isNaN(userId) && (userId > 0))) {
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
        }
    });

    function fetchProductsData(page, tagIds, currentUrl, currentUserId) {
        const uri = document.location.pathname;
        $.ajax({
            url: `/api/v1/todo/pagination?page=${page}`,
            data: {
                filter: tagIds,
                current_user_id: currentUserId,
            },
            success: function (data) {
                let url = `${currentUrl}?`
                if (tagIds) {
                    url += `filter=${tagIds}&page=${page}`;
                } else {
                    url += `page=${page}`;
                }
                window.history.replaceState(null, null, url);
                $(`.todo-lists-body`).html(data);
            }
        });
    }

    $(document).on(`click`, `.pagination a`, function () {
        event.preventDefault();
        let page = $(this).attr(`href`).split(`page=`)[1];
        let paramSort = $(this).attr(`href`).split(`?`)[1].split(`&`)[0];
        let tagIds = paramSort.split(`filter=`)[1];
        fetchProductsData(page, tagIds, currentUrl, currentUserId);
    });

    let arr = [];
    $(document).on(`click`, `.tag-click`, function () {
        let id = parseInt($(this).data('id'));
        const index = arr.indexOf(id);
        if (index !== -1) {
            arr.splice(index, 1);
        } else {
            arr.push(id);
        }
        if (arr.length) {
            let tagIds = arr.join('_');
            $.ajax({
                url: `/api/v1/filter?filter=${tagIds}&current_user_id=${currentUserId}`,
                method: `GET`,
                contentType: false,
                processData: false,
                cache: false,
                success: function (data) {
                    let url = `${currentUrl}?filter=${tagIds}`;
                    window.history.replaceState(null, null, url);
                    $(`.todo-lists-body`).html(data);
                    $(`#an_btn-filter-reset`).removeClass(`an_btn-reset-hidden`);
                }
            });
        }
    });
});