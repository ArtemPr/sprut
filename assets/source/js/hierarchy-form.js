/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

document.addEventListener("DOMContentLoaded", function (event) {
    const scopes = document.querySelectorAll('.hierarchy__scope');

    if (scopes) {
        scopes.forEach(scope => scope.addEventListener('click', function () {
            if (scope.classList.contains('hierarchy__scope--parent')) {
                let parent = scope.parentNode;
                //console.log(parent);
                const parentInput = scope.querySelector('input');
                let childrenInputs = parent.querySelectorAll('.hierarchy__child-scopes .js-checkbox-scope');
                // console.log(childrenInputs);
                if (parentInput.checked === true && childrenInputs) {
                    childrenInputs.forEach(childInput => childInput.checked = true);
                }
                if (parentInput.checked === false && childrenInputs) {
                    childrenInputs.forEach(childInput => childInput.checked = false);
                }
            }
        }))
    }
})

