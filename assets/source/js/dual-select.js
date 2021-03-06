/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */
require('sortablejs');
//import Sortable from 'sortablejs';
import {Sortable, MultiDrag} from 'sortablejs';
Sortable.mount(new MultiDrag());

document.addEventListener("DOMContentLoaded", function (event) {

    // function focusOnFirst() {
    //     let ItemsToSelect = leftPanel.querySelectorAll('.dual-listbox__item');
    //     if(ItemsToSelect) {
    //         ItemsToSelect[0].classList.add("dual-listbox__item--selected")
    //     }
    // }

    function customDualSelect(container, select) {
        let dualItems = [];
        let leftPanel = null;
        let rightPanel = null;
        let addOneBtn = null;
        let addAllBtn = null;
        let removeOneBtn = null;
        let removeAllBtn = null;
        if (container) {
            dualItems = container.querySelectorAll(".dual-listbox__item");
            leftPanel = container.querySelector(".dual-controls-select");
            rightPanel = container.querySelector(".dual-listbox__selected");
            addOneBtn = container.querySelector('[name="add_one"]');
            addAllBtn = container.querySelector('[name="add_all"]');
            removeOneBtn = container.querySelector('[name="remove_one"]');
            removeAllBtn = container.querySelector('[name="remove_all"]');
        }
        let selectedItems = [];
        let ctrlOn = false;
        let shiftOn = false;
        let hiddenOptions = [];
        let valuesList = [];
        let leftItems = [];

        if (select) {
            hiddenOptions = select.querySelectorAll('option');
            hiddenOptions.forEach(hiddenOption => valuesList.push(hiddenOption.textContent));
        }

        if (container && dualItems && select && leftPanel !== null && rightPanel !== null) {

            function unselectAll() {
                dualItems.forEach((dualItem) =>
                    dualItem.classList.remove('dual-listbox__item--selected')
                )
            }

            // drag-and-drop
            new Sortable(leftPanel, {
                multiDrag: true,
                selectedClass: 'multi--selected',
                group: 'shared', // set both lists to same group
                animation: 150
            });


            new Sortable(rightPanel, {
                multiDrag: true,
                selectedClass: 'multi--selected',
                group: 'shared',
                animation: 150
            });

            leftPanel.addEventListener('dragend', function () {
                addToHiddenSelect();
                unselectAll();
                leftItems = rightPanel.querySelectorAll('.dual-listbox__item');
                if(leftItems.length > 0) {
                    leftItems[0].classList.add('dual-listbox__item--selected');
                }
            })

            rightPanel.addEventListener('dragend', function () {
                addToHiddenSelect();
                unselectAll();
                leftItems = leftPanel.querySelectorAll('.dual-listbox__item');
                if(leftItems.length > 0) {
                    leftItems[0].classList.add('dual-listbox__item--selected');
                }
            })

            dualItems.forEach(dualItem => {
                    if (dualItem.classList.contains('dual-listbox__item--selected')) {
                        selectedItems.push(dualItem);
                    }
                }
            )

            if (selectedItems.length === 0) {
                dualItems[0].classList.add("dual-listbox__item--selected")
            } else {
                selectedItems.forEach(selectedItem => {
                    leftPanel.removeChild(selectedItem);
                    rightPanel.appendChild(selectedItem);
                    selectedItem.classList.remove("dual-listbox__item--selected");
                })
                let leftRoles = [];
                leftRoles =  leftPanel.querySelectorAll('.dual-listbox__item');
                if(leftRoles.length > 0) {
                    leftRoles[0].classList.add("dual-listbox__item--selected");
                }
            }

            addOneBtn.addEventListener("click", addOne);
            addAllBtn.addEventListener("click", addAll);
            removeOneBtn.addEventListener("click", removeOne);
            removeAllBtn.addEventListener("click", removeAll);

            dualItems.forEach((dualItem) => {
                dualItem.addEventListener("click", toggleSelected);
            });

            document.addEventListener('keydown', function (e) {
                if (e.code === 'ControlLeft' || e.code === 'ControlRight') {
                    ctrlOn = true;
                } else if (e.code === 'ShiftLeft' || e.code === 'ShiftRight') {
                    shiftOn = true;
                }
            });

            document.addEventListener('keyup', function (e) {
                if (e.code === 'ControlLeft' || e.code === 'ControlRight') {
                    ctrlOn = false;
                } else if (e.code === 'ShiftLeft' || e.code === 'ShiftRight') {
                    shiftOn = false;
                }
            });

            function addToHiddenSelect() {
                hiddenOptions.forEach(hiddenOption => {
                    hiddenOption.removeAttribute("selected");
                })
                let itemsToSend = rightPanel.querySelectorAll('.dual-listbox__item');
                let index = null;
                if (itemsToSend.length > 0) {
                    itemsToSend.forEach(itemToSend => {
                        index = valuesList.indexOf(itemToSend.textContent);
                        if (index !== -1) {
                            hiddenOptions[index].setAttribute("selected", true);
                        }
                    })
                }
            }

            function toggleSelected(event) {
                if (shiftOn === true) {
                    let firstSelected = container.querySelector('.dual-listbox__item--selected');
                    if (firstSelected) {
                        let lastSelected = event.target;
                        if (firstSelected.parentElement === lastSelected.parentElement) {
                            let parent = firstSelected.parentElement;
                            let allChildren = parent.querySelectorAll('.dual-listbox__item');
                            let allChildrenArr = Array.from(parent.querySelectorAll('.dual-listbox__item'));
                            let firstIndex = allChildrenArr.indexOf(firstSelected);
                            let lastIndex = allChildrenArr.indexOf(lastSelected);
                            if (firstIndex !== -1 && lastIndex !== -1) {
                                if (firstIndex < lastIndex) {
                                    for (let i = firstIndex; i <= lastIndex; i++) {
                                        allChildren[i].classList.add('dual-listbox__item--selected');
                                    }
                                } else {
                                    for (let i = lastIndex; i <= firstIndex; i++) {
                                        allChildren[i].classList.add('dual-listbox__item--selected');
                                    }
                                }
                            }
                        }
                    }
                    return
                }
                if (ctrlOn === false) {
                    unselectAll();
                    this.classList.toggle('dual-listbox__item--selected')
                }
                if (ctrlOn === true) {
                    this.classList.add('dual-listbox__item--selected');
                }
            }

            function addOne() {
                selectedItems = leftPanel.querySelectorAll('.dual-listbox__item--selected');
                if (selectedItems) {
                    selectedItems.forEach(item => {
                        leftPanel.removeChild(item);
                        rightPanel.appendChild(item);
                        item.classList.remove("dual-listbox__item--selected");
                    })
                }
                addToHiddenSelect();
                leftItems = leftPanel.querySelectorAll('.dual-listbox__item');
                if(leftItems.length > 0) {
                    leftItems[0].classList.add('dual-listbox__item--selected');
                }
            }

            function removeOne() {
                selectedItems = rightPanel.querySelectorAll('.dual-listbox__item--selected');
                if (selectedItems) {
                    selectedItems.forEach(item => {
                        rightPanel.removeChild(item);
                        leftPanel.appendChild(item);
                        item.classList.remove("dual-listbox__item--selected");
                    })
                }
                addToHiddenSelect();
                leftItems = rightPanel.querySelectorAll('.dual-listbox__item');
                if(leftItems.length > 0) {
                    leftItems[0].classList.add('dual-listbox__item--selected');
                }
            }

            function addAll() {
                let itemsToMove = leftPanel.querySelectorAll('.dual-listbox__item');
                if (itemsToMove) {
                    itemsToMove.forEach(item => {
                        leftPanel.removeChild(item);
                        rightPanel.appendChild(item);
                    })
                }
                addToHiddenSelect();
                unselectAll();
            }

            function removeAll() {
                let itemsToMove = rightPanel.querySelectorAll('.dual-listbox__item');
                if (itemsToMove) {
                    itemsToMove.forEach(item => {
                        rightPanel.removeChild(item);
                        leftPanel.appendChild(item);
                    })
                }
                addToHiddenSelect();
                unselectAll();
            }

        }

    }

    // ?????????? ?????????????? ???, ?????????? ???????????????????? ?????????????? div
    // ?????????????? ???????????? ?? ???????????? dual-listbox__item" ?? ????.
    // ?????? ???? ?????????????? "?????????????? ????????????????????????"

    const userDualBoxAdd = document.querySelector('.dual-listbox--user-add');
    let hiddenSelectAdd = document.querySelector('#user-select-hidden--add');

    customDualSelect(userDualBoxAdd, hiddenSelectAdd)

    const employerDualBoxAdd = document.querySelector('.dual-listbox--employer-add');
    let hiddenSelectEmployer = document.querySelector('#employer-select-hidden--add');
    customDualSelect(employerDualBoxAdd, hiddenSelectEmployer)
    const potentialDualBoxAdd = document.querySelector('.dual-listbox--potential-add');
    let hiddenSelectPotential = document.querySelector('#potential-select-hidden--add');
    customDualSelect(potentialDualBoxAdd, hiddenSelectPotential)

    let userDualBoxUpdate = null
    let hiddenSelectUpdate = null
    let employerDualBoxUpdate = null
    let employerSelectUpdate = null
    let potentialDualBoxUpdate = null
    let potentialSelectUpdate = null

    const editTriggerBtn = document.querySelector('[href="#AdminUserEditingPanel"]');
    const programTriggerBtn = document.querySelector('[href="#programEditingPanel"]');
    if (editTriggerBtn) {
        editTriggerBtn.addEventListener('click', function () {
            let interval_update = setInterval(function () {
                userDualBoxUpdate = document.querySelector('.dual-listbox--user-update');
                hiddenSelectUpdate = document.querySelector('#user-select-hidden--update');
                if (userDualBoxUpdate !== null && hiddenSelectUpdate !== null) {
                    customDualSelect(userDualBoxUpdate, hiddenSelectUpdate)
                    clearInterval(interval_update);
                }
            }, 1500)
        })
    }
    if (programTriggerBtn) {
        programTriggerBtn.addEventListener('click', function () {
            let interval_program_update = setInterval(function () {
                employerDualBoxUpdate = document.querySelector('.dual-listbox--employer-update');
                employerSelectUpdate = document.querySelector('#employer-select-hidden--update');
                potentialDualBoxUpdate = document.querySelector('.dual-listbox--potential-update');
                potentialSelectUpdate = document.querySelector('#potential-select-hidden--update');
                if (employerDualBoxUpdate !== null && employerSelectUpdate !== null && potentialDualBoxUpdate !== null && potentialSelectUpdate !== null) {
                    customDualSelect(employerDualBoxUpdate, employerSelectUpdate)
                    customDualSelect(potentialDualBoxUpdate, potentialSelectUpdate)
                    clearInterval(interval_program_update);
                }
            }, 1500)
        })
    }
});
