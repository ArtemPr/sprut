/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */


require('sortablejs');
import Sortable from 'sortablejs';

document.addEventListener("DOMContentLoaded", function (event) {

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
            if(tabs !== null) {
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
            console.log(url);
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
                        //    console.log('change ', value)
                        switch (selectColored.value) {
                            case "1":
                                selectColored.className = 'form-control select-new';
                                break;
                            case "2":
                                selectColored.className = 'form-control select-check';
                                break;
                            case "3":
                                selectColored.className = 'form-control select-work';
                                break;
                            case "4":
                                selectColored.className = 'form-control select-approved';
                                break;
                            case "5":
                                selectColored.className = 'form-control select-ready';
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
})



document.addEventListener("DOMContentLoaded", function (event) {
    let items = document.querySelector('#items-list');
    if (items != null) {
        var sortable = new Sortable(items, {
            animation: 150
        });
    }
});
