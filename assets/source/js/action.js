/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

//test

let top_btn = document.querySelectorAll('.btn');

for (var i = 0; i < top_btn.length; i++) {
    top_btn[i].onclick = function(){
        let select_string = document.querySelectorAll('.is-selected');
        let select_id = (select_string.length > 0) ? select_string[0].getAttribute('data-string') : null;
        let type = this.getAttribute('href');
        if (select_id != null) {
            if (type === '#userEditingPanel') {
                getFormData('userEditingPanel','edit_user', select_id);
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
            document.getElementById(id).innerHTML=data;
        });
}

function getDeleteData(select_id) {

}
