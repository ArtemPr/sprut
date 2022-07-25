/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */
document.addEventListener("DOMContentLoaded", function (event) {

    const userDualBox = document.querySelector('.dual-listbox--user-add');
    let hiddenSelect = document.querySelector('[name="roles[]"]');
    let dualItems = [];
    let leftPanel = null;
    let rightPanel = null;
    let addOneBtn = null;
    let addAllBtn = null;
    let removeOneBtn = null;
    let removeAllBtn = null;
    if (userDualBox) {
        dualItems = userDualBox.querySelectorAll(".dual-listbox__item");
        leftPanel = userDualBox.querySelector(".dual-controls-select");
        rightPanel = userDualBox.querySelector(".dual-listbox__selected");
        addOneBtn = userDualBox.querySelector('[name="add_one"]');
        addAllBtn = userDualBox.querySelector('[name="add_all"]');
        removeOneBtn = userDualBox.querySelector('[name="remove_one"]');
        removeAllBtn = userDualBox.querySelector('[name="remove_all"]');
    }
    let selectedItems = [];
    let ctrlOn = false;

    let hiddenOptions = [];
    let valuesList = [];

    if (hiddenSelect) {
        hiddenOptions = hiddenSelect.querySelectorAll('option');
        hiddenOptions.forEach(hiddenOption => valuesList.push(hiddenOption.textContent));
    }

    if (userDualBox && dualItems && hiddenSelect && leftPanel !== null && rightPanel !== null) {
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
            }
        });
        document.addEventListener('keyup', function (e) {
            if (e.code === 'ControlLeft' || e.code === 'ControlRight') {
                ctrlOn = false;
            }
        });
    }

    // function focusOnFirst() {
    //     let ItemsToSelect = leftPanel.querySelectorAll('.dual-listbox__item');
    //     if(ItemsToSelect) {
    //         ItemsToSelect[0].classList.add("dual-listbox__item--selected")
    //     }
    // }

    function addToHiddenSelect() {
        hiddenOptions.forEach(hiddenOption => {
            hiddenOption.setAttribute("selected", false);
        })
        let itemsToSend = rightPanel.querySelectorAll('.dual-listbox__item');
        let index = null;
        console.log('selectedItems toggle ', itemsToSend);
        if (itemsToSend.length > 0) {
            itemsToSend.forEach(itemToSend => {
                index = valuesList.indexOf(itemToSend.textContent);
                console.log('found in select ', index);
                if (index !== -1) {
                    //  hiddenOptions[index].selected = true;
                    hiddenOptions[index].setAttribute("selected", true);
                }
            })
        }
        //   console.log('hiddenSelect after ', hiddenSelect);
    }

    function toggleSelected() {
        if (ctrlOn === false) {
            dualItems.forEach((dualItem) =>
                dualItem.classList.remove('dual-listbox__item--selected')
            )
            this.classList.toggle('dual-listbox__item--selected')
        } else {
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
        //   focusOnFirst();
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
        // focusOnFirst();
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
        dualItems.forEach((dualItem) =>
            dualItem.classList.remove('dual-listbox__item--selected')
        )
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
        dualItems.forEach((dualItem) =>
            dualItem.classList.remove('dual-listbox__item--selected')
        )
    }
    // focusOnFirst();

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
    //    let hiddenSelect = document.querySelector('[name="roles[]"]');
        let hiddenOptions = [];
        let valuesList = [];

        if (select) {
            hiddenOptions = select.querySelectorAll('option');
            // console.log('hiddenOptions ', hiddenOptions);
            hiddenOptions.forEach(hiddenOption => valuesList.push(hiddenOption.textContent));
        }

        if (container && dualItems && hiddenSelect && leftPanel !== null && rightPanel !== null) {
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
                }
            });
            document.addEventListener('keyup', function (e) {
                if (e.code === 'ControlLeft' || e.code === 'ControlRight') {
                    ctrlOn = false;
                }
            });
        }

        function addToHiddenSelect() {
            hiddenOptions.forEach(hiddenOption => {
                hiddenOption.setAttribute("selected", false);
            })
            let itemsToSend = rightPanel.querySelectorAll('.dual-listbox__item');
            let index = null;
            console.log('selectedItems toggle ', itemsToSend);
            if (itemsToSend.length > 0) {
                itemsToSend.forEach(itemToSend => {
                    index = valuesList.indexOf(itemToSend.textContent);
                    console.log('found in select ', index);
                    if (index !== -1) {
                        hiddenOptions[index].setAttribute("selected", true);
                    }
                })
            }
        }

        function toggleSelected() {
            if (ctrlOn === false) {
                dualItems.forEach((dualItem) =>
                    dualItem.classList.remove('dual-listbox__item--selected')
                )
                this.classList.toggle('dual-listbox__item--selected')
            } else {
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
            dualItems.forEach((dualItem) =>
                dualItem.classList.remove('dual-listbox__item--selected')
            )
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
            dualItems.forEach((dualItem) =>
                dualItem.classList.remove('dual-listbox__item--selected')
            )
        }
    }
});
