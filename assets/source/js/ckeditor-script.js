/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

import ClassicEditor from 'ckeditor5-build-classic-plus';

// настройка toolbar
// https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format


document.addEventListener("DOMContentLoaded", function (event) {
    let ckeditorItems = [];
    ckeditorItems = document.querySelectorAll('.editor');

    if (ckeditorItems.length > 0) {
        for (let i = 0; i < ckeditorItems.length; i++) {
            ClassicEditor
                .create(ckeditorItems[i], {
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
                    // для загрузки картинок
                    // simpleUpload: {
                    //     // The URL that the images are uploaded to.
                    //     uploadUrl: "http://example.com",
                    //
                    //     // Enable the XMLHttpRequest.withCredentials property if required.
                    //     withCredentials: true,
                    //
                    //     // Headers sent along with the XMLHttpRequest to the upload server.
                    //     headers: {
                    //         "X-CSRF-TOKEN": "CSFR-Token",
                    //         Authorization: "Bearer <JSON Web Token>"
                    //     }
                    // }
                })
                .then(editor => {
                    window.editor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });
        }
    }
})

