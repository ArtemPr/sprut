/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

import ClassicEditor from "ckeditor5-build-classic-plus";

document.addEventListener("DOMContentLoaded", function (event) {
    let productionRow = document.querySelector('.production-row');
    if (productionRow) {
        productionRow.addEventListener('click', function () {
            console.log('productionRow click');
            let select_string = document.querySelectorAll('.is-selected');
            let select_id = (select_string.length > 0) ? select_string[0].getAttribute('data-string') : null;

            async function getProductionData() {
                let url = location.protocol + '//' + location.host + '/discipline_production/' + select_id;
                // let result = await fetch(url);
                // console.log('result ', result);
                let data = await fetch(url).then((result) => result.text());
                // console.log('data ', data);
                let box = document.querySelector('#disciplineEditor');
                if (box !== null && data !== null) {
                    box.innerHTML = data;
                }

                let interval_update = setInterval(function () {
                    let ckeditorItem = null;
                    ckeditorItem = document.querySelector('#editor--discipline');

                    if (ckeditorItem !== null) {
                        clearInterval(interval_update);
                            ClassicEditor
                                .create(ckeditorItem, {
                                    toolbar: {
                                        items: [
                                            'heading', '|',
                                            'fontfamily', 'fontsize', '|',
                                            'alignment', '|',
                                            'fontColor', 'fontBackgroundColor', '|',
                                            'bold', 'italic', 'strikethrough', 'underline', 'subscript', 'superscript', '|',
                                            'link', '|',
                                            'outdent', 'indent', '|',
                                            'bulletedList', 'numberedList', '|',
                                            'code', '|',
                                            'insertTable', '|',
                                            'blockQuote', '|',
                                            'undo', 'redo'
                                        ],
                                        shouldNotGroupWhenFull: true
                                    },
                                })
                                .then(editor => {
                                    window.editor = editor;
                                })
                                .catch(err => {
                                    console.error(err.stack);
                                });

                    }
                },500)
            }
            getProductionData();
        })
    }

    let authorRow = document.querySelector('.author-text-row');
    if(authorRow) {
        authorRow.oncontextmenu = (function(e){
            e.preventDefault();
            authorRow.click();
        })
    }
})
