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
            //  console.log('changeFgosBtn click');

            const firstRow = '<tr>' +
                '<td>-</td>' +
                '<td>' +
                '<input type="text" name="comp_name[]" class="form-control" value="">' +
                '</td>' +
                '<td class="text-center">' +
                '<button class="delete_string">X</button>' +
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
        '<button class="delete_string">X</button>' +
        '</td>' +
        '</tr>'

    if (add_string_table && btn_add_line_create) {
    //    const first_tr = add_string_table.querySelector('tbody tr')
    //     let first_close = first_tr.querySelector('.delete_string');
    //     first_close.disabled = true;

        function removeRow(el) {
            this.removeEventListener('click', () => removeRow(el))
            el.remove();
        }

        btn_add_line_create.addEventListener('click', function () {

            add_string_table.querySelector('tbody').insertAdjacentHTML('afterbegin', firstRow);
            let rowElement = add_string_table.querySelector('tbody tr');
            //   console.log('rowElement ', rowElement);
            let rowCross = rowElement.querySelector('tbody tr .delete_string');
            //    console.log('rowCross ', rowCross);
            rowCross.addEventListener('click', () => removeRow(rowElement))

        });
    }
});


