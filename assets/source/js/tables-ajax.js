/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

// изменение данных в таблице при GET параметре ajax=true

const ajaxTable = document.querySelector('.ajax-table');
let selectValue = 25;
let tableUrl = `${location.protocol}//${location.host}/administrator/kafedra?ajax=true&&on_page=${selectValue}`;
let selectOnPage = document.querySelector('#on_page_selector');
let sortIcons = document.querySelectorAll('.sort-icon');
let paginationLinks = document.querySelectorAll('.page-link');

// function clearOldListeners() {
//     sortIcons.forEach(sortIcon => sortIcon.removeEventListener('click'));
// }

// функции обработки кликов
function paginationClicker(event) {
    event.preventDefault();
    let paginationLinkData = this.getAttribute('href');
    getTableData(paginationLinkData);
}

function sortClicker(event) {
    event.preventDefault();
    let hrefData = this.getAttribute('href');
    let hrefDataArr = hrefData.split('&');
    if (hrefDataArr.includes('ajax=true')) {
        tableUrl = hrefData;
    } else {
        tableUrl = `${hrefData}&ajax=true`;
    }
    getTableData(tableUrl);
}

function rowsClicker() {
    allRows.forEach(rowDeep => {
        rowDeep.classList.remove('is-selected');
    })
    this.classList.add('is-selected');

    //  this.querySelector(".selected-checkbox").checked = true ???;
    if (body && userEditingPanel && userEditingOverlay) {
        row.addEventListener('dblclick', function(){
            body.classList.add("user-editing-panel-opened");
            userEditingPanel.classList.add("show");
            userEditingOverlay.classList.add("show");
        })
    }
}

// при обновлении таблицы надо заново навешивать события на новые элементы
function setPaginationListeners() {
    paginationLinks = document.querySelectorAll('.page-link');
    if (paginationLinks) {
        paginationLinks.forEach(paginationLink => {
            paginationLink.addEventListener('click',  paginationClicker)
        })
    }
}

function setSortListeners() {
    sortIcons = document.querySelectorAll('.sort-icon');
    if(sortIcons) {
        sortIcons.forEach(sortIcon => {
            sortIcon.addEventListener('click', sortClicker)
        })
    }
}

function manageRows() {
    let allRows = document.querySelectorAll('.user-table-row');
    if (allRows.length > 0) {
        allRows.forEach(row => {
            row.addEventListener('click', rowsClicker)
        })
    }
}

// получаем данные, заменяем таблицу, пишем запрос в адресную строку, заново вешаем слушатели
async function getTableData(tableUrl) {
    let data = await fetch(tableUrl).then((result) => result.text());
    if (ajaxTable) {
        ajaxTable.innerHTML = data;
    }
    setSortListeners();
    setPaginationListeners();
    history.pushState('', '', tableUrl);
    manageRows();
}

// начало сценария
if (selectOnPage) {
    selectOnPage.addEventListener('change', function () {
        selectValue = selectOnPage.value;
        tableUrl = `${location.protocol}//${location.host}/administrator/kafedra?on_page=${selectValue}&ajax=true`;
        getTableData(tableUrl);
    })
}

if (sortIcons) {
    setSortListeners();
}

// работа с формой Создать

const btn_form = document.querySelector('button[data-action="send-form"]');
const addItemForm = document.querySelector('.form-create');
const userEditingPanel = document.querySelector("#userEditingPanel");
const userEditingOverlay = document.querySelector("#userEditingOverlay");
const userCreationPanel = document.querySelector('#userCreationPanel');
const body = document.querySelector("body");

/*чтобы скрыть форму по submit меняем аттрибуты кнопки
https://getbootstrap.com/docs/5.2/components/offcanvas/
<button data-form-id="kafedra-create" data-action="send-form" type="submit"
class="btn btn-primary ms-auto"
!!! data-bs-dismiss="offcanvas" aria-label="Close" !!!
>Сохранить </button>*/

if (addItemForm && btn_form) {
    addItemForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        const form_id = btn_form.getAttribute('data-form-id');
        if (form_id !== undefined) {
            const sectionName = form_id.split('-')[0];
            let data = new FormData(addItemForm);

            // fetch POST
            if (sectionName) {
                let response = await fetch(`/api/${sectionName}`, {
                    method: 'POST',
                    body: data,
                })
                let result = await response.json();
                console.log(result)
                if (result.result === "success") {
                    console.log('success post!');
                        await getTableData(tableUrl);
                        // находим созданную строку по id и выделяем
                        let rowId = result.id;
                        let activeRow = document.querySelector(`[data-string="${rowId}"]`);
                    if (activeRow) {
                        let firstRow = document.querySelector('.user-table-row');
                        if (firstRow) {
                            firstRow.classList.remove('is-selected');
                        }
                        activeRow.classList.add('is-selected');
                        activeRow.scrollIntoView({block: "center", behavior: "smooth"});
                    }
                }
            }
        }
    })
}
