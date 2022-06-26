/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

let top_btn = document.querySelectorAll('.btn');

for (var i = 0; i < top_btn.length; i++) {
    top_btn[i].onclick = function () {
        let select_string = document.querySelectorAll('.is-selected');
        let select_id = (select_string.length > 0) ? select_string[0].getAttribute('data-string') : null;
        let container_type = (select_string.length > 0) ? select_string[0].getAttribute('data-type') : null;
        let type = this.getAttribute('href');
        if (select_id != null) {
            if (type === '#userEditingPanel') {
                getFormData('kafedra_update-form', container_type, select_id);
            } else if (type === '#userRemoveModal') {
                getDeleteData(select_id);
            }
        }
    };
}

let token = 'a78c9bd272533646ae84683a2eabb817';

async function getFormData(id, type, select_id = null) {


    url = location.protocol + '//' + location.host + '/api/' + type + '/' + select_id;

    let data = await fetch(url).then((result) => result.text());
    let out = JSON.parse(data);
    out.forEach(function (o) {
        console.log(o);
        // @TODO сдесь закончить разбор данных и заполнить форму
    });
}

function getDeleteData(select_id) {

}
