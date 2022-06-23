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

if (selectOnPage) {
    selectOnPage.addEventListener('change', function () {
        selectValue = selectOnPage.value;
        tableUrl = `${location.protocol}//${location.host}/administrator/kafedra?on_page=${selectValue}&ajax=true`;
        getTableData(tableUrl);
    })
}

// при обновлении таблицы надо заново навешивать события на новую пагинацию
function setPaginationListeners() {
    paginationLinks = document.querySelectorAll('.page-link');
    if (paginationLinks) {
        paginationLinks.forEach(paginationLink => {
            paginationLink.addEventListener('click', function (event) {
                event.preventDefault();
                let paginationLinkData = paginationLink.getAttribute('href');
                getTableData(paginationLinkData);
            })
        })
    }
}

// при обновлении таблицы надо заново навешивать события на новые стрелочки
function setSortListeners() {
    sortIcons.forEach(sortIcon => {
        sortIcon.addEventListener('click', function (event) {
            event.preventDefault();
            let hrefData = sortIcon.getAttribute('href');
            let hrefDataArr = hrefData.split('&');
            if (hrefDataArr.includes('ajax=true')) {
                tableUrl = hrefData;
            } else {
                tableUrl = `${hrefData}&ajax=true`;
            }
            getTableData(tableUrl);
        })
    })
}

if (sortIcons) {
    setSortListeners();
}

// получаем данные, заменяем таблицу, пишем запрос в адресную строку, заново вешаем слушатели
async function getTableData(tableUrl) {
    let data = await fetch(tableUrl).then((result) => result.text());
    if (ajaxTable) {
        ajaxTable.innerHTML = data;
    }
    sortIcons = document.querySelectorAll('.sort-icon');
    if (sortIcons) {
        setSortListeners();
    }
    setPaginationListeners();
    history.pushState('', '', tableUrl);
}

// работа с формой Создать

const btn_form = document.querySelector('button[data-action="send-form"]');
const form_id = btn_form.getAttribute('data-form-id');
const addItemForm = document.querySelector('.form-create');

if (addItemForm && btn_form) {
    addItemForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        if (form_id !== undefined) {
            const sectionName = form_id.split('-')[0];
            let data = new FormData(addItemForm);
            if (sectionName) {
                let response = await fetch(`/api/${sectionName}`, {
                    method: 'POST',
                    body: data,
                })
                let result = await response.json();
                if (result == "succes") {
                    console.log('succes post!');
                    getTableData(tableUrl);
                }
            }
        }
    })
}
