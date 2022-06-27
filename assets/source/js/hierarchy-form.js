/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

document.addEventListener("DOMContentLoaded", function(event) {
    const scopes = document.querySelectorAll('.hierarchy__scope');

    if(scopes) {
        scopes.forEach(scope=>scope.addEventListener('click', function(){
            if(scope.classList.contains('parent-scope')){
                console.log('parent');
                const parentInput = scope.querySelector('input');

            let childrenInputs = document.querySelectorAll('.child-scopes .js-checkbox-scope');
            console.log(childrenInputs);
            if(parentInput.checked === true && childrenInputs) {
                console.log('parent checked = true ');
                childrenInputs.forEach(childInput=> childInput.checked = true);
            }
            if (parentInput.checked === false && childrenInputs){
                console.log('parent checked = false ');
                childrenInputs.forEach(childInput=> childInput.checked = false);
            }
            }
        }))
    }
})

