/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */
// временно элементов нет
document.addEventListener("DOMContentLoaded", function (event) {

    const userDualBox = document.querySelector('.dual-listbox--user-add');
    const dualItems = userDualBox.querySelectorAll(".dual-listbox__item");
    const leftPanel = userDualBox.querySelector(".dual-controls-select");
    const rightPanel = userDualBox.querySelector(".dual-listbox__selected");
    const addOneBtn = userDualBox.querySelector('[name="add_one"]');
    const addAllBtn = userDualBox.querySelector('[name="add_all"]');
    const removeOneBtn = userDualBox.querySelector('[name="remove_one"]');
    const removeAllBtn = userDualBox.querySelector('[name="remove_all"]');

    if (userDualBox && addOneBtn && leftPanel && rightPanel && dualItems) {
        dualItems[0].classList.add("dual-listbox__item--selected");
        addOneBtn.addEventListener("click", addOne);
        addAllBtn.addEventListener("click", addAll);
        removeOneBtn.addEventListener("click", removeOne);
        removeAllBtn.addEventListener("click", removeAll);

        dualItems.forEach((dualItem) => {
            dualItem.addEventListener("click", toggleSelected);
        });
    }

    function focusOnFirst() {
        let ItemsToSelect = leftPanel.querySelectorAll('.dual-listbox__item');
        console.log('ItemsToSelect ', ItemsToSelect);
        if(ItemsToSelect) {
            ItemsToSelect[0].classList.add("dual-listbox__item--selected");
        }
    }

    function toggleSelected() {
        dualItems.forEach((dualItem) =>
            dualItem.classList.remove("dual-listbox__item--selected")
        );
        this.classList.toggle("dual-listbox__item--selected");

    }

    function addOne() {
        let elToMove = leftPanel.querySelector('.dual-listbox__item--selected');
        if(elToMove) {
            leftPanel.removeChild(elToMove);
            rightPanel.appendChild(elToMove);
            elToMove.classList.remove("dual-listbox__item--selected");
        }
        focusOnFirst();
    }

    function removeOne() {
        let elToMove = rightPanel.querySelector('.dual-listbox__item--selected');
        if(elToMove) {
            rightPanel.removeChild(elToMove);
            leftPanel.appendChild(elToMove);
            elToMove.classList.remove("dual-listbox__item--selected");
        }
        focusOnFirst();
    }

    function addAll(){
        let itemsToMove = leftPanel.querySelectorAll('.dual-listbox__item');
        if (itemsToMove) {
            itemsToMove.forEach(item => {
                leftPanel.removeChild(item);
                rightPanel.appendChild(item);
            })
        }
    }

    function removeAll(){
        let itemsToMove = rightPanel.querySelectorAll('.dual-listbox__item');
        if (itemsToMove) {
            itemsToMove.forEach(item => {
                rightPanel.removeChild(item);
                leftPanel.appendChild(item);
            })
        }
    }
});
