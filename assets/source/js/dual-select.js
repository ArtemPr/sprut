/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */
require('sortablejs');
import Sortable from 'sortablejs';

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

        if (select) {
            hiddenOptions = select.querySelectorAll('option');
            hiddenOptions.forEach(hiddenOption => valuesList.push(hiddenOption.textContent));
        }

        if (container && dualItems && hiddenSelect && leftPanel !== null && rightPanel !== null) {

            function unselectAll() {
                dualItems.forEach((dualItem) =>
                    dualItem.classList.remove('dual-listbox__item--selected')
                )
            }

            // drag-and-drop
            new Sortable(leftPanel, {
                group: 'shared', // set both lists to same group
                animation: 150
            });

            new Sortable(rightPanel, {
                group: 'shared',
                animation: 150
            });

            leftPanel.addEventListener('dragend', function(){
                addToHiddenSelect();
                unselectAll();
            })

            rightPanel.addEventListener('dragend', function(){
                addToHiddenSelect();
                unselectAll();
            })

            dualItems[0].classList.add("dual-listbox__item--selected")
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
                }  else if (e.code === 'ShiftLeft' || e.code === 'ShiftRight') {
                    shiftOn = false;
                }
            });

        function addToHiddenSelect() {
            hiddenOptions.forEach(hiddenOption => {
                hiddenOption.setAttribute("selected", false);
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
                let firstSelected = document.querySelector('.dual-listbox__item--selected');
                if(firstSelected) {
                    let lastSelected = event.target;
                    if (firstSelected.parentElement === lastSelected.parentElement) {
                        let parent = firstSelected.parentElement;
                        let allChildren = parent.querySelectorAll('.dual-listbox__item');
                        let allChildrenArr = Array.from(parent.querySelectorAll('.dual-listbox__item'));
                        let firstIndex = allChildrenArr.indexOf(firstSelected);
                        let lastIndex = allChildrenArr.indexOf(lastSelected);
                        if(firstIndex !== -1 && lastIndex !== -1) {
                            if(firstIndex < lastIndex) {
                                for (let i = firstIndex; i <= lastIndex; i++ ) {
                                    allChildren[i].classList.add('dual-listbox__item--selected');
                                }
                            } else {
                                for (let i = lastIndex; i <= firstIndex; i++ ) {
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
            } if (ctrlOn === true) {
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

    // вызов функции ↑, нужно подставить обертку div
    // скрытый селект и классы dual-listbox__item" и др.
    // как на модалке "Создать пользователя"

    const userDualBox = document.querySelector('.dual-listbox--user-add');
    let hiddenSelect = document.querySelector('[name="roles[]"]');

    customDualSelect(userDualBox, hiddenSelect)
});
