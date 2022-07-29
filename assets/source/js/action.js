/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */


require('sortablejs');
import Sortable from 'sortablejs';
import Cookies from 'js-cookie'

document.addEventListener("DOMContentLoaded", function (event) {

    // скрытие боковой панели ↓
    const hideBtn = document.querySelector('.hide-btn');
    const asidePanel = document.querySelector('.aside-panel');
    const pageWrapper = document.querySelector('.page-wrapper');
    const navLinkWords = document.querySelectorAll('.aside-panel .nav-link-title');
    let asideHiddenValue = Cookies.get('aside_hidden');

    // переводим строку из куки в булевое значение
    if (asideHiddenValue === 'true') {
        asideHiddenValue = true;
    } else if (asideHiddenValue === 'false') {
        asideHiddenValue = false;
    } else {
        Cookies.set('aside_hidden', 'false', { expires: 7 });
        asideHiddenValue = false;
    }

    if (hideBtn && asidePanel && pageWrapper && navLinkWords) {
        // скрываем панель сразу, если кука true
        if (asideHiddenValue === true) {
            navLinkWords.forEach(navLinkWord => {
                navLinkWord.classList.add('nav-link--hidden');
            })
            asidePanel.classList.add('aside--hidden');
            hideBtn.classList.add('hide-btn--hidden');
            pageWrapper.classList.add('page-wrapper--full');
        }

        hideBtn.addEventListener('click', function () {
            navLinkWords.forEach(navLinkWord => {
                navLinkWord.classList.toggle('nav-link--hidden');
            })
            asidePanel.classList.toggle('aside--hidden');
            hideBtn.classList.toggle('hide-btn--hidden');
            pageWrapper.classList.toggle('page-wrapper--full');
            asideHiddenValue = !asideHiddenValue;
            Cookies.set('aside_hidden', asideHiddenValue, { expires: 7 })
        })
    }
    // скрытие боковой панели  ↑

    let top_btn = document.querySelectorAll('.btn');

    for (var i = 0; i < top_btn.length; i++) {
        top_btn[i].onclick = function () {
            let select_string = document.querySelectorAll('.is-selected');
            let select_id = (select_string.length > 0) ? select_string[0].getAttribute('data-string') : null;
            let container_type = (select_string.length > 0) ? select_string[0].getAttribute('data-type') : null;

            let typeAction = this.getAttribute('data-action');
            let controller = this.getAttribute('data-controller');

            if (select_id != null) {
                if (typeAction === 'edit') {
                    getFormData(controller + 'EditingPanel', controller + '_edit', select_id);
                }
            }
        };
    }

    let token = 'a78c9bd272533646ae84683a2eabb817';

    async function getFormData(container, controller, select_id = null) {

        let url = location.protocol + '//' + location.host + '/form/' + controller + '/' + select_id;
        console.log(url);
        let data = await fetch(url).then((result) => result.text());

        let box = document.querySelector('#' + container);
        if (box !== null && data !== null) {
            box.innerHTML = data;

            const dualControlsSelects = document.querySelectorAll(".dual-controls-select_update");
            for (let i = 0; i < dualControlsSelects.length; ++i) {
                let dualControlsSelect = dualControlsSelects[i];
                new DualListbox(dualControlsSelect, {
                    availableTitle: "Available numbers",
                    selectedTitle: "Selected numbers",
                    addButtonText: ">",
                    removeButtonText: "<",
                    addAllButtonText: ">>",
                    removeAllButtonText: "<<",
                    searchPlaceholder: "Поиск",
                });
            }

            let tabs = document.querySelector('.nav-tabs');
            if (tabs !== null) {
            }

        }
    }


    // Удаление
    const delete_btn = document.querySelector('.delete_action');
    if (delete_btn != null) {
        delete_btn.addEventListener('click', async function (event) {
            event.preventDefault();
            let select_string = document.querySelector('.is-selected');
            let select_id = (select_string != null) ? select_string.getAttribute('data-string') : null;
            let controller = (select_string != null) ? select_string.getAttribute('data-type') : null;

            let url = location.protocol + '//' + location.host + '/api/' + controller + '_hide/' + select_id;
            //    console.log(url);
            await fetch(url).then((result) => result.text());

            select_string.parentNode.removeChild(select_string);

            let line = document.querySelectorAll('.user-table-row');
            if (line[0] != null) {
                line[0].classList.add('is-selected');
            }
        });
    }

    const changeDisciplineBtn = document.querySelector('.btn-group [data-action="edit"]');
    if (changeDisciplineBtn) {
        changeDisciplineBtn.addEventListener('click', function () {
            setTimeout(() => {
                const selectColored = document.getElementById('select-colored');
                if (selectColored) {
                    selectColored.addEventListener('change', function () {
                        const value = selectColored.options[selectColored.selectedIndex].value
                        switch (selectColored.value) {
                            case "1":
                                selectColored.className = 'form-control select-new';
                                break;
                            case "2":
                                selectColored.className = 'form-control select-check';
                                break;
                            case "3":
                                selectColored.className = 'form-control select-revision';
                                break;
                            case "4":
                                selectColored.className = 'form-control select-accept';
                                break;
                            case "5":
                                selectColored.className = 'form-control select-done';
                                break;
                            default:
                                selectColored.className = 'form-control select-new';
                                break;
                        }
                    })
                }
            }, 1000);
        })
    }

    // размер загружаемого файла в шаблонах документов
    const fileInputAdd = document.querySelector('#dt-formFile');
    let sizeInputAdd = document.querySelector('[name="dt-file-size"]');
    if (fileInputAdd && sizeInputAdd) {
        fileInputAdd.addEventListener('change', function () {
            sizeInputAdd.value = Math.round(this.files[0].size / 1024) + " kb";
        })
    }

    const fileInputEdit = document.querySelector('#dt-formFile-edit');
    let sizeInputEdit = document.querySelector('[name="dt-file-size"]');
    if (fileInputEdit && sizeInputEdit) {
        fileInputEdit.addEventListener('change', function () {
            sizeInputEdit.value = Math.round(this.files[0].size / 1024) + " kb";
        })
    }
})

// Настройка отображения колонок таблицы
document.addEventListener("DOMContentLoaded", function (event) {
    let dragItems = document.querySelector('#drag-interface');
    if (dragItems != null) {
        let sortable = new Sortable(dragItems, {
            animation: 150
        });
    }
});
