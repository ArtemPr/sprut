/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

document.addEventListener("DOMContentLoaded", function (event) {
    const fgosControllers = document.querySelectorAll('[data-controller="fgos"]')
    let addFgosBtn = null;
    let changeFgosBtn = null;
    if (fgosControllers) {
        fgosControllers.forEach(fgosController => {
            let controllerAttribute = fgosController.getAttribute('data-action');
            if (controllerAttribute === 'add') {
                addFgosBtn = fgosController;
            } else if (controllerAttribute === 'edit') {
                changeFgosBtn = fgosController
            }
        })
    }

    if (changeFgosBtn !== null) {
        changeFgosBtn.addEventListener('click', function () {

            const firstRow = '<tr>' +
                '<td>-</td>' +
                '<td>' +
                '<input type="text" name="comp_name[]" class="form-control" value="">' +
                '</td>' +
                '<td class="text-center">' +
                '<button class="delete_string">'+

                '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d63939" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
               '<path stroke="none" d="M0 0h24v24H0z" fill="none"/>' +
             '<line x1="4" y1="7" x2="20" y2="7" />' +
               '<line x1="10" y1="11" x2="10" y2="17" />' +
                '<line x1="14" y1="11" x2="14" y2="17" />' +
               '<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />' +
             '<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />' +
                '</svg>'+
                '</button>' +
                '</td>' +
                '</tr>'

            setTimeout(() => {
                let add_btn = document.querySelector("body").querySelector('#add_string_create_add');
                let add_tbl = document.querySelector("body").querySelector('#add_string_table_add');

                if (add_btn !== null) {
                    add_btn.addEventListener('click', function () {
                        function removeRow(el) {
                            //    console.log('rowCross click');
                            this.removeEventListener('click', () => removeRow(el))
                            el.remove();
                        }

                        add_tbl.querySelector('tbody').insertAdjacentHTML('afterbegin', firstRow);
                        let rowElement = add_tbl.querySelector('tbody tr');
                        //   console.log('rowElement ', rowElement);
                        let rowCross = rowElement.querySelector('tbody tr .delete_string');
                        //    console.log('rowCross ', rowCross);
                        rowCross.addEventListener('click', () => removeRow(rowElement))
                    });
                }
            }, 1500);
        })
    }

    const btn_add_line_create = document.querySelector('#add_string_create');
    const add_string_table = document.querySelector('#add_string_table');
    const firstRowCross = add_string_table.querySelector('tbody tr .delete_string');
    firstRowCross.addEventListener('click', function(){
        firstRowCross.parentElement.parentElement.remove();
    })
    const firstRow = '<tr>' +
        '<td>-</td>' +
        '<td>' +
        '<input type="text" name="comp_name[]" class="form-control" value="">' +
        '</td>' +
        '<td class="text-center">' +
        '<button class="delete_string">'+
        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d63939" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
        '<path stroke="none" d="M0 0h24v24H0z" fill="none"/>' +
        '<line x1="4" y1="7" x2="20" y2="7" />' +
        '<line x1="10" y1="11" x2="10" y2="17" />' +
        '<line x1="14" y1="11" x2="14" y2="17" />' +
        '<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />' +
        '<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />' +
        '</svg>'+
        '</button>' +
        '</td>' +
        '</tr>'

    if (add_string_table && btn_add_line_create) {
        function removeRow(el) {
            this.removeEventListener('click', () => removeRow(el))
            el.remove();
        }
        btn_add_line_create.addEventListener('click', function () {
            add_string_table.querySelector('tbody').insertAdjacentHTML('afterbegin', firstRow);
            let rowElement = add_string_table.querySelector('tbody tr');
            let rowCross = rowElement.querySelector('tbody tr .delete_string');
            rowCross.addEventListener('click', () => removeRow(rowElement))
        });
    }
});


