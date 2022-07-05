"use strict";

// получаем из npm пакета
require('imask');

let userControls = document.querySelectorAll('[data-controller="user"]');
let addUserBtn = null;
let editUserBtn = null;
if (userControls) {
    userControls.forEach(userControl => {
        if (userControl.getAttribute('data-action') === 'add') {
            addUserBtn = userControl;
        } else if ((userControl.getAttribute('data-action') === 'edit')) {
            editUserBtn = userControl;
        }
    })

    let addTel = document.querySelector("[name='phone']");
    if(addTel) {
        let addMaskOptions = {
            mask: '+{7}(000)000-00-00'
        };
        let addMask = IMask(addTel, addMaskOptions);
    }

    if (editUserBtn !== null) {
        editUserBtn.addEventListener('click', function () {
            setTimeout(() => {
                let editTel = document.querySelector(".form-update [name='phone']");
           //     console.log(editTel);
                let editMaskOptions = {
                    mask: '+{7}(000)000-00-00'
                };
                let editMask = IMask(editTel, editMaskOptions);
            }, 1000);
        })
    }
}



// заготовка под плитки на стартовой странице

// import Masonry from 'masonry-layout';
//
// var msnry = new Masonry( '.columns__list', {
//     itemSelector: '.columns__link',
//     columnWidth: '.grid-sizer',
//     percentPosition: true
// });

/**
 * Check on Touch Device
 */



function isTouchDevice() {
    return (
        "ontouchstart" in window ||
        navigator.maxTouchPoints > 0 ||
        navigator.msMaxTouchPoints > 0
    );
}

const body = document.querySelector("body");
const userTableRows = document.querySelectorAll(".user-table-row");
const userEditingPanel = document.querySelector("#userEditingPanel");
const userEditingPanelCloser = document.querySelector("#jsEditingPanelCloser");
const userEditingOverlay = document.querySelector("#userEditingOverlay");
const userCreationPanel = document.querySelector("#userCreationPanel");

if (isTouchDevice()) {
    body.classList.add("is-touch-device");
}

/**
 * Users Interaction
 */

// function showUserEditingPanel() {
//     body.classList.add("user-editing-panel-opened");
//     userEditingPanel.classList.add("show");
//     userEditingOverlay.classList.add("show");
// }

for (let i = 0; i < userTableRows.length; ++i) {
    let userItem = userTableRows[i];

    /* detect double tap event */
    let timeout;
    let lastTap = 0;
    userItem.addEventListener("touchstart", function (event) {
        let currentTime = new Date().getTime();
        let tapLength = currentTime - lastTap;
        clearTimeout(timeout);
        if (tapLength < 500 && tapLength > 0) {
            showUserEditingPanel();
            event.preventDefault();
        }
        lastTap = currentTime;
    });
}

if (userEditingPanelCloser !== undefined && userEditingPanelCloser !== null) {
    userEditingPanelCloser.addEventListener("click", function () {
        body.classList.remove("user-editing-panel-opened");
        userEditingPanel.classList.remove("show");
        userEditingOverlay.classList.remove("show");
    });
}

if (userEditingOverlay !== undefined && userEditingOverlay !== null) {
    userEditingOverlay.addEventListener("click", function () {
        body.classList.remove("user-editing-panel-opened");
        userEditingPanel.classList.remove("show");
        userEditingOverlay.classList.remove("show");
    });
}

/**
 * Editable Table
 */

function showEditableRowPanel(panel, overlay) {
    if (panel !== null && overlay !== null) {
        body.classList.add("user-editing-panel-opened");
        panel.classList.add("show");
        overlay.classList.add("show");
    }
}

const editableTables = document.querySelectorAll(".editable-table");
const customAsidePanels = document.querySelectorAll(".custom-aside-panel");
const customOverlay = document.querySelector(".custom-overlay");
const customPanelCloser = document.querySelector(".custom-panel-closer");
for (let i = 0; i < editableTables.length; ++i) {
    let editableTable = editableTables[i];
    let childEditableRows = editableTable.querySelectorAll(
        ".editable-table-row"
    );

    for (let i = 0; i < childEditableRows.length; ++i) {
        let editableTableRow = childEditableRows[i];
        editableTableRow.addEventListener("click", function () {
            childEditableRows.forEach((f) => f.classList.remove("is-selected"));
            editableTableRow.classList.add("is-selected");

            childEditableRows.forEach(
                (f) => (f.querySelector(".selected-checkbox").checked = false)
            );
            editableTableRow.querySelector(".selected-checkbox").checked = true;
        });

        editableTableRow.addEventListener("dblclick", function () {
            let targetPanel = document.querySelector(
                this.getAttribute("data-target-panel")
            );
            let targetOverlay = document.querySelector(
                this.getAttribute("data-target-overlay")
            );
            showEditableRowPanel(targetPanel, targetOverlay);
        });

        /* detect double tap event */
        let timeout;
        let lastTap = 0;
        editableTableRow.addEventListener("touchstart", function (event) {
            let targetPanel = document.querySelector(
                this.getAttribute("data-target-panel")
            );
            let targetOverlay = document.querySelector(
                this.getAttribute("data-target-overlay")
            );
            let currentTime = new Date().getTime();
            let tapLength = currentTime - lastTap;
            clearTimeout(timeout);
            if (tapLength < 500 && tapLength > 0) {
                showEditableRowPanel(targetPanel, targetOverlay);
                event.preventDefault();
            }
            lastTap = currentTime;
        });
    }
}
if (customPanelCloser != null) {
    customPanelCloser.addEventListener("click", function () {
        body.classList.remove("user-editing-panel-opened");
        this.closest(".custom-aside-panel").classList.remove("show");
        customOverlay.classList.remove("show");
    });
}
if (customOverlay != null) {
    customOverlay.addEventListener("click", function () {
        body.classList.remove("user-editing-panel-opened");
        customAsidePanels.forEach((f) => f.classList.remove("show"));
        this.classList.remove("show");
    });
}

/**
 * User Photo Upload
 */

window.addEventListener("load", function () {
    const userPhotoInputs = document.querySelectorAll(".user-photo-input");
    for (let i = 0; i < userPhotoInputs.length; ++i) {
        let userPhotoInput = userPhotoInputs[i];
        userPhotoInput.addEventListener("change", function () {
            if (this.files && this.files[0]) {
                const thisParent = this.parentElement;
                const thisImg = thisParent.querySelector(".user-photo-img");
                thisImg.onload = () => {
                    URL.revokeObjectURL(thisImg.src);
                };
                thisImg.src = URL.createObjectURL(this.files[0]);
            }
        });
    }
});

const dualControlsSelects = document.querySelectorAll(".dual-controls-select_create");
for (let i = 0; i < dualControlsSelects.length; ++i) {
    let dualControlsSelect = dualControlsSelects[i];
    new DualListbox(dualControlsSelect, {
        availableTitle: "Available numbers",
        selectedTitle: "Selected numbers",
        addButtonText: ">",
        removeButtonText: "<",
        addAllButtonText: ">>",
        removeAllButtonText: "<<",
        searchPlaceholder: "Поиск",
    });
}

/**
 * Search Clear
 */

/*используется шаблон templates/administrator/user/block/search_panel.html.twig
руками задан id инпуту и кнопочке id jsClearSearch, похоже, что начальное решение было
изящным, но что-то пошло не так, поэтому правка */
window.addEventListener("load", function () {
    const clearSearchBtn = document.querySelector("#jsClearSearch");
    const searchInput = document.querySelector("#input-search");
    //console.log('jsClearSearch');
    if (clearSearchBtn !== undefined && clearSearchBtn !== null) {
        clearSearchBtn.addEventListener("click", function (e) {
            //e.preventDefault();
            // const closestForm = clearSearchBtn.closest("form");
            // console.log(closestForm);
            // const thisSearchInput = closestForm.querySelector('.search-input');
            // thisSearchInput.value = "";
            if (searchInput) {
                searchInput.value = "";
            }
        });
    }


    const submitSearch = document.querySelector('#submitSearch');
    const searchForm = document.querySelector('#searchForm');
    if (submitSearch !== undefined && submitSearch !== null) {
        submitSearch.addEventListener("click", function (e) {
            e.preventDefault();
            searchForm.submit();
        });
    }
});

/**
 * Multi Steps Form
 */

window.addEventListener("load", function () {
    const multiStepsForms = document.querySelectorAll(".multi-steps-form");
    for (let i = 0; i < multiStepsForms.length; ++i) {
        let multiStepsForm = multiStepsForms[i];
        let multiStepsFormSteps = multiStepsForm.querySelectorAll(
            ".multi-steps-form__step"
        );

        for (let j = 0; j < multiStepsFormSteps.length; ++j) {
            let multiStepsFormStep = multiStepsFormSteps[j];
            let multiStepsFormBtns = multiStepsFormStep.querySelectorAll(
                ".multi-steps-form__btn"
            );

            for (let k = 0; k < multiStepsFormBtns.length; ++k) {
                let multiStepsFormBtn = multiStepsFormBtns[k];

                multiStepsFormBtn.addEventListener("click", function () {
                    let targetStep = document.querySelector(
                        this.getAttribute("data-target-step")
                    );
                    if (targetStep !== null) {
                        multiStepsFormSteps.forEach((f) =>
                            f.classList.remove("active")
                        );
                        targetStep.classList.add("active");
                    }
                });
            }
        }
    }
});

/**
 * Regular Tree Interact
 */

window.addEventListener("load", function () {
    const regularTrees = document.querySelectorAll(".regular-tree");
    for (let i = 0; i < regularTrees.length; ++i) {
        let regularTree = regularTrees[i];
        let canSelectEls = regularTree.querySelectorAll(".can-select");

        for (let j = 0; j < canSelectEls.length; ++j) {
            let canSelectEl = canSelectEls[j];
            canSelectEl.addEventListener("click", function () {
                canSelectEls.forEach((f) => f.classList.remove("selected"));
                this.classList.add("selected");
            });
        }
    }
});

/**
 * Users Table Drag'n'Drop
 */

function addTableDragNDrop() {
    if (isTouchDevice()) {
        return false;
    }

    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("usersTable");

        let draggingEle;
        let draggingColumnIndex;
        let placeholder;
        let list;
        let isDraggingStarted = false;

        // The current position of mouse relative to the dragging element
        let x = 0;
        let y = 0;

        // Swap two nodes
        const swap = function (nodeA, nodeB) {
            const parentA = nodeA.parentNode;
            const siblingA =
                nodeA.nextSibling === nodeB ? nodeA : nodeA.nextSibling;

            // Move `nodeA` to before the `nodeB`
            nodeB.parentNode.insertBefore(nodeA, nodeB);

            // Move `nodeB` to before the sibling of `nodeA`
            parentA.insertBefore(nodeB, siblingA);
        };

        // Check if `nodeA` is on the left of `nodeB`
        const isOnLeft = function (nodeA, nodeB) {
            // Get the bounding rectangle of nodes
            const rectA = nodeA.getBoundingClientRect();
            const rectB = nodeB.getBoundingClientRect();

            return rectA.left + rectA.width / 2 < rectB.left + rectB.width / 2;
        };

        const cloneTable = function () {
            const rect = table.getBoundingClientRect();

            list = document.createElement("div");
            list.classList.add("t-clone-list");
            list.style.position = "absolute";
            list.style.left = `${rect.left}px`;
            list.style.top = `${rect.top}px`;
            table.parentNode.insertBefore(list, table);

            // Hide the original table
            table.style.visibility = "hidden";

            // Get all cells
            const originalCells = [].slice.call(
                table.querySelectorAll("tbody td")
            );

            const originalHeaderCells = [].slice.call(
                table.querySelectorAll("th")
            );
            const numColumns = originalHeaderCells.length;

            // Loop through the header cells
            originalHeaderCells.forEach(function (headerCell, headerIndex) {
                const width = parseInt(
                    window.getComputedStyle(headerCell).width
                );

                // Create a new table from given row
                const item = document.createElement("div");
                item.classList.add("t-draggable");

                const newTable = document.createElement("table");
                newTable.setAttribute("class", "t-clone-table");
                newTable.style.width = `${width}px`;

                // Header
                const th = headerCell.cloneNode(true);
                let newRow = document.createElement("tr");
                newRow.appendChild(th);
                newTable.appendChild(newRow);

                const cells = originalCells.filter(function (c, idx) {
                    return (idx - headerIndex) % numColumns === 0;
                });
                cells.forEach(function (cell) {
                    const newCell = cell.cloneNode(true);
                    newCell.style.width = `${width}px`;
                    newRow = document.createElement("tr");
                    newRow.appendChild(newCell);
                    newTable.appendChild(newRow);
                });

                item.appendChild(newTable);
                list.appendChild(item);
            });
        };

        const mouseDownHandler = function (e) {
            if (e.button !== 0 || e.target.classList[0] === "sort-icon")
                return false;

            draggingColumnIndex = [].slice
                .call(table.querySelectorAll("th"))
                .indexOf(e.target);

            // Determine the mouse position
            x = e.clientX - e.target.offsetLeft;
            y = e.clientY - e.target.offsetTop;

            // Attach the listeners to `document`
            document.addEventListener("mousemove", mouseMoveHandler);
            setTimeout(function () {
                document.addEventListener("mouseup", mouseUpHandler);
            }, 100);
        };

        const mouseMoveHandler = function (e) {
            if (e.button !== 0) return false;

            if (!isDraggingStarted) {
                isDraggingStarted = true;

                cloneTable();

                draggingEle = [].slice.call(list.children)[draggingColumnIndex];
                draggingEle.classList.add("t-dragging");

                // Let the placeholder take the height of dragging element
                // So the next element won't move to the left or right
                // to fill the dragging element space
                placeholder = document.createElement("div");
                placeholder.classList.add("t-placeholder");
                draggingEle.parentNode.insertBefore(
                    placeholder,
                    draggingEle.nextSibling
                );
                placeholder.style.width = `${draggingEle.offsetWidth}px`;
            }

            // Set position for dragging element
            draggingEle.style.position = "absolute";
            draggingEle.style.top = `${
                draggingEle.offsetTop + e.clientY - y
            }px`;
            draggingEle.style.left = `${
                draggingEle.offsetLeft + e.clientX - x
            }px`;

            // Reassign the position of mouse
            x = e.clientX;
            y = e.clientY;

            // The current order
            // prevEle
            // draggingEle
            // placeholder
            // nextEle
            const prevEle = draggingEle.previousElementSibling;
            const nextEle = placeholder.nextElementSibling;

            // // The dragging element is above the previous element
            // // User moves the dragging element to the left
            if (prevEle && isOnLeft(draggingEle, prevEle)) {
                // The current order    -> The new order
                // prevEle              -> placeholder
                // draggingEle          -> draggingEle
                // placeholder          -> prevEle
                swap(placeholder, draggingEle);
                swap(placeholder, prevEle);
                return;
            }

            // The dragging element is below the next element
            // User moves the dragging element to the bottom
            if (nextEle && isOnLeft(nextEle, draggingEle)) {
                // The current order    -> The new order
                // draggingEle          -> nextEle
                // placeholder          -> placeholder
                // nextEle              -> draggingEle
                swap(nextEle, placeholder);
                swap(nextEle, draggingEle);
            }
        };

        const mouseUpHandler = function () {
            // Remove the placeholder
            placeholder && placeholder.parentNode.removeChild(placeholder);

            draggingEle.classList.remove("t-dragging");
            draggingEle.style.removeProperty("top");
            draggingEle.style.removeProperty("left");
            draggingEle.style.removeProperty("position");

            // Get the end index
            const endColumnIndex = [].slice
                .call(list.children)
                .indexOf(draggingEle);

            isDraggingStarted = false;

            // Remove the `list` element
            list.parentNode.removeChild(list);

            // Move the dragged column to `endColumnIndex`
            if (table !== undefined && table !== null) {
                table.querySelectorAll("tr").forEach(function (row) {
                    const cells = [].slice.call(row.querySelectorAll("th, td"));
                    draggingColumnIndex > endColumnIndex
                        ? cells[endColumnIndex].parentNode.insertBefore(
                            cells[draggingColumnIndex],
                            cells[endColumnIndex]
                        )
                        : cells[endColumnIndex].parentNode.insertBefore(
                            cells[draggingColumnIndex],
                            cells[endColumnIndex].nextSibling
                        );
                });

                // Bring back the table
                table.style.removeProperty("visibility");
            }
            // Remove the handlers of `mousemove` and `mouseup`
            document.removeEventListener("mousemove", mouseMoveHandler);
            document.removeEventListener("mouseup", mouseUpHandler);
        };

        if (table !== undefined && table !== null) {
            table.querySelectorAll("th").forEach(function (headerCell) {
                headerCell.classList.add("t-draggable");
                headerCell.addEventListener("mousedown", mouseDownHandler);
            });
        }
    });
}

addTableDragNDrop();
