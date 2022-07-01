/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

document.addEventListener("DOMContentLoaded", function (event) {
    const passwordIcon = document.querySelector('[title="Show password"]');
    const inputPassword = document.querySelector('#inputPassword');
    let isHidden = true;
    if (passwordIcon && inputPassword) {
        passwordIcon.addEventListener('click', function () {
            isHidden = !isHidden;
            if (isHidden === true) {
                inputPassword.type = 'password';
            } else {
                inputPassword.type = 'text';
            }
        })
    }
})

