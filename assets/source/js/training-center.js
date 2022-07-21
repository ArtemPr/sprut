/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

document.addEventListener("DOMContentLoaded", function (event) {
    const editTrigger = document.querySelector('[href="#training_centreEditingPanel"]');
    const addColorBtn = document.querySelector('#add_custom_color');
    let updateColorBtn = document.querySelector('#update_custom_color');
    const colorTabCreate = document.querySelector('#uc_create_tab7');
    let colorTabUpdate = document.querySelector('#uc_create_tab8');
    const colorPickerRowAdd = '<div class="mb-3 custom-color-row">' +
        '<input type="color" name="corporate-color-custom--add"  class="form-control form-control-color" value="#206bc4" title="Выберите цвет">' +
        '<button class="delete-color-btn" title="Удалить цвет">' +
        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d63939" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
        '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
        '<line x1="4" y1="7" x2="20" y2="7"></line>' +
        '<line x1="10" y1="11" x2="10" y2="17"></line>' +
        '<line x1="14" y1="11" x2="14" y2="17"></line>' +
        '<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>' +
        '<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>' +
        '</svg>' +
        '</button>' +
        '</div>'
    const colorPickerRowUpdate = '<div class="mb-3 custom-color-row">' +
        '<input type="color" name="corporate-color-custom--update"  class="form-control form-control-color" value="#206bc4" title="Выберите цвет">' +
        '<button class="delete-color-btn" title="Удалить цвет">' +
        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d63939" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
        '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
        '<line x1="4" y1="7" x2="20" y2="7"></line>' +
        '<line x1="10" y1="11" x2="10" y2="17"></line>' +
        '<line x1="14" y1="11" x2="14" y2="17"></line>' +
        '<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>' +
        '<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>' +
        '</svg>' +
        '</button>' +
        '</div>'
    if(addColorBtn && colorTabCreate) {
        addColorBtn.addEventListener('click', function(){
            addColorBtn.insertAdjacentHTML('beforebegin', colorPickerRowAdd);
            let deleteRowBtns = colorTabCreate.querySelectorAll('.custom-color-row .delete-color-btn');
            deleteRowBtns[deleteRowBtns.length-1].addEventListener('click', function(){
                this.parentElement.remove();
            })
        })
    }

    if(editTrigger) {
        // слушаем кнопку Изменить, потому что на второе открытие модалки элементы перерисовываются
        editTrigger.addEventListener('click', function(){
            // используем интервал, чтобы найти элементы во второй модалке, они сразу не отрисовываются со всей стр
            let interval_update = setInterval(function (){
                updateColorBtn = document.querySelector('#update_custom_color');
                colorTabUpdate = document.querySelector('#uc_create_tab8');
                if(updateColorBtn && colorTabUpdate) {
                    clearInterval(interval_update);
                    updateColorBtn.addEventListener('click', function(){
                        console.log('updateColorBtn click');
                        updateColorBtn.insertAdjacentHTML('beforebegin', colorPickerRowUpdate);
                        let deleteRowBtns = colorTabUpdate.querySelectorAll('.custom-color-row .delete-color-btn');
                        deleteRowBtns[deleteRowBtns.length-1].addEventListener('click', function(){
                            this.parentElement.remove();
                        })
                    })
                }
            }, 1500);
        })
    }
})

