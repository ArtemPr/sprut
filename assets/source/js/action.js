/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

let top_btn = document.querySelectorAll('.btn');

for (var i = 0; i < top_btn.length; i++) {
    top_btn[i].onclick = function () {
        let select_string = document.querySelectorAll('.is-selected');
        let select_id = (select_string.length > 0) ? select_string[0].getAttribute('data-string') : null;
        let type = this.getAttribute('href');
        if (select_id != null) {
            if (type === '#userEditingPanel') {
                getFormData('userEditingPanel', 'edit_user', select_id);
            } else if (type === '#userRemoveModal') {
                getDeleteData(select_id);
            }
        }
    };
}

let token = 'a78c9bd272533646ae84683a2eabb817';

async function getFormData(id, type, select_id = null) {

    url = 'http://' + location.host + '/form/' + type + '/' + select_id + '?t=' + token;

    return fetch(url, {
        method: "GET",
    })
        .then(function (response) {
            return response.text();
        })
        .then(function (data) {
            document.getElementById(id).innerHTML = data;
        });
}

function getDeleteData(select_id) {

}

// изменение данных в таблице при GET параметре ajax=true

const ajaxTable = document.querySelector('.ajax-table');
let selectValue = 25;
let tableUrl = `http://${location.host}/administrator/kafedra?ajax=true&&on_page=${selectValue}`;
let selectOnPage = document.querySelector('#on_page_selector');
let sortIcons = document.querySelectorAll('.sort-icon');

if (selectOnPage) {
    selectOnPage.addEventListener('change', function () {
        selectValue = selectOnPage.value;
        tableUrl = `http://${location.host}/administrator/kafedra?on_page=${selectValue}&ajax=true`;
        // console.log(tableUrl);
        getTableData(tableUrl);
        //console.log(selectValue);
    })
}

function setSortListeners() {
    sortIcons.forEach(sortIcon => {
        sortIcon.addEventListener('click', function (event) {
            event.preventDefault();
            let hrefData = sortIcon.getAttribute('href');
            // console.log(hrefData);
            let hrefDataArr = hrefData.split('&');
            if (hrefDataArr.includes('ajax=true')) {
                tableUrl = hrefData;
            } else {
                tableUrl = `${hrefData}&ajax=true`;
            }
            //  console.log(tableUrl);
            getTableData(tableUrl);
        })
    })
}

if (sortIcons) {
    setSortListeners();
}

async function getTableData() {
    let data = await fetch(tableUrl).then((result) => result.text());
    ajaxTable.innerHTML = data;
    sortIcons = document.querySelectorAll('.sort-icon');
    if (sortIcons) {
        setSortListeners();
    }
//    console.log(data);
}

