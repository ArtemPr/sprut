/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

document.addEventListener("DOMContentLoaded", function (event) {
    const changeRoleBtn = document.querySelector(
        '.btn-group [data-action="edit"]'
    );
    const createRoleBtn = document.querySelector(
        '.btn-group [data-action="add"]'
    );
    if (changeRoleBtn) {
        changeRoleBtn.addEventListener("click", manipulateCheckboxes);
    }
    if (createRoleBtn) {
        createRoleBtn.addEventListener("click", manipulateCheckboxes);
    }

    function manipulateCheckboxes() {
        setTimeout(() => {
            // модалки с чекбоксами 2 шт, но в начальном DOM их нет.
            // при первом открытии модалки список с чекбоксом 1, при открытии после второго - становится 2 списка
            let rootLists = document.querySelectorAll('.hierarchy__group-list');
            let allCheckboxes =[];
            if(rootLists.length === 1) {
                allCheckboxes = rootLists[0].querySelectorAll("input[type=checkbox]");
            } else if (rootLists.length === 2) {
                const btnType = this.getAttribute('data-action');
                if (btnType){
                    if (btnType === 'add') {
                        allCheckboxes = rootLists[0].querySelectorAll("input[type=checkbox]");
                    } else if (btnType === 'edit') {
                        allCheckboxes = rootLists[1].querySelectorAll("input[type=checkbox]");
                    }
                }
            }

            if(allCheckboxes.length > 0) {
                allCheckboxes.forEach((element) => {
                    element.addEventListener("click", function () {
                        let parentLi = this.closest("li");
                        const childrenCheckboxes = parentLi.querySelectorAll(
                            ".hierarchy__child-scopes input[type=checkbox]"
                        );
                        childrenCheckboxes.forEach((child) => {
                            child.checked = this.checked;
                        });
                        let parentLevel = parentLi.getAttribute("data-level");
                        if (parentLevel !== "1") {
                            parentLevel = Number(parentLevel);
                            if (this.checked === true) {
                                for (let i = parentLevel - 1; i >= 1; i--) {
                                    parentLi = parentLi.closest(`[data-level="${i}"]`);
                                    let checkItem = parentLi.querySelector(
                                        "input[type=checkbox]"
                                    );
                                    checkItem.checked = true;
                                }
                            } else {
                                for (let i = parentLevel; i >= 1; i--) {
                                    parentLi = this.closest(`[data-level="${i}"]`);
                                    let checkers = parentLi.querySelectorAll(
                                        'input[type="checkbox"]:checked'
                                    );
                                    if (checkers.length <= 1) {
                                        this.closest(
                                            `[data-level="${i}"]`
                                        ).querySelector(
                                            "input[type=checkbox]"
                                        ).checked = false;
                                    }
                                }
                            }
                        }
                    });
                });
            }

            // const scopes = document.querySelectorAll(".hierarchy__scope");
            // if (scopes) {
            //     scopes.forEach((scope) =>
            //         scope.addEventListener("click", function () {
            //             console.log("this ", this);
            //             console.log(
            //                 "scope.closest ",
            //                 scope.closest('li')
            //             );
            //             let closestParentLi = scope.closest('li');
            //             let scopeLevel = closestParentLi.getAttribute('data-level');
            //             if(scopeLevel > 1) {
            //
            //             }
            //
            //             if (
            //                 scope.classList.contains("hierarchy__scope--parent")
            //             ) {
            //                 let parent = scope.parentNode;
            //                 //console.log(parent);
            //                 const parentInput = scope.querySelector("input");
            //              //   parentInput.style.outline = "2px dotted red";
            //                 let childrenInputs = parent.querySelectorAll(
            //                     ".hierarchy__child-scopes .js-checkbox-scope"
            //                 );
            //                 // console.log(childrenInputs);
            //                 if (
            //                     parentInput.checked === true &&
            //                     childrenInputs
            //                 ) {
            //                     childrenInputs.forEach(
            //                         (childInput) => (childInput.checked = true)
            //                     );
            //                 }
            //                 if (
            //                     parentInput.checked === false &&
            //                     childrenInputs
            //                 ) {
            //                     childrenInputs.forEach(
            //                         (childInput) => (childInput.checked = false)
            //                     );
            //                 }
            //             }
            //         })
            //     );
            // }
        }, 1000);
    }
});
