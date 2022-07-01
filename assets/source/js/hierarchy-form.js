/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

document.addEventListener("DOMContentLoaded", function (event) {
    const changeRoleBtn = document.querySelector(
        '.btn-group [data-action="edit"]'
    );
    console.log(changeRoleBtn);

    if (changeRoleBtn) {
        changeRoleBtn.addEventListener("click", manipulateCheckboxes);
    }

    function manipulateCheckboxes() {
        setTimeout(() => {
            const scopes = document.querySelectorAll(".hierarchy__scope");
            if (scopes) {
                scopes.forEach((scope) =>
                    scope.addEventListener("click", function () {
                        console.log("this ", this);
                        console.log(
                            "scope.closest ",
                            scope.closest('li')
                        );
                        let closestParentLi = scope.closest('li');
                        let scopeLevel = closestParentLi.getAttribute('data-level');
                        if(scopeLevel > 1) {

                        }



                        if (
                            scope.classList.contains("hierarchy__scope--parent")
                        ) {
                            let parent = scope.parentNode;
                            //console.log(parent);
                            const parentInput = scope.querySelector("input");
                            parentInput.style.outline = "2px dotted red";
                            let childrenInputs = parent.querySelectorAll(
                                ".hierarchy__child-scopes .js-checkbox-scope"
                            );
                            // console.log(childrenInputs);
                            if (
                                parentInput.checked === true &&
                                childrenInputs
                            ) {
                                childrenInputs.forEach(
                                    (childInput) => (childInput.checked = true)
                                );
                            }
                            if (
                                parentInput.checked === false &&
                                childrenInputs
                            ) {
                                childrenInputs.forEach(
                                    (childInput) => (childInput.checked = false)
                                );
                            }
                        }
                    })
                );
            }
        }, 1000);
    }

    manipulateCheckboxes();
});
