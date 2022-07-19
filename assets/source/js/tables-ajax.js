/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

import flatpickr from "flatpickr";
import { Russian } from "flatpickr/dist/l10n/ru.js"

// изменение данных в таблице при GET параметре ajax=true
document.addEventListener("DOMContentLoaded", function (event) {

    function initCalendars(){
        const flatpickrStart = document.querySelector('#flatpickr-start');
        const flatpickrEnd = document.querySelector('#flatpickr-end');

        if (flatpickrStart) {
            flatpickr(document.querySelector('#flatpickr-start'), {
                "locale": Russian,
                wrap: true,
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });
        }

        if (flatpickrEnd) {
            flatpickr(document.querySelector('#flatpickr-end'), {
                "locale": Russian,
                wrap: true,
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            })
        }
    }

    initCalendars();

    const ajaxTable = document.querySelector(".ajax-table");
    let selectValue = 25;
    let selectOnPage = document.querySelector("#on_page_selector");
    //   console.log('selectOnPage ', selectOnPage);
    let sectionName = null;
    if (selectOnPage !== null) {
        sectionName = document
            .querySelector("#on_page_selector")
            .getAttribute("data-path");
        //    console.log('sectionName ', sectionName);
    }
    let sortIcons = document.querySelectorAll(".sort-icon");
    let paginationLinks = document.querySelectorAll(".page-link");
    let allRows = document.querySelectorAll(".user-table-row");
    const editBtn = document.querySelector("[data-action=edit]");

    function isTouchDevice() {
        return (
            "ontouchstart" in window ||
            navigator.maxTouchPoints > 0 ||
            navigator.msMaxTouchPoints > 0
        );
    }

    /**
     * Users Table Drag'n'Drop
     */

    function addTableDragNDrop() {
        if (isTouchDevice()) {
            return false;
        }
        let table = document.getElementById("usersTable");
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
            //    console.log('cloneTable')
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
            let parentTh = e.target.closest('th');
            if (e.button !== 0 || parentTh.classList[0] === "sort-icon")
                return false;

            draggingColumnIndex = [].slice
                .call(table.querySelectorAll("th"))
                .indexOf(parentTh);

            // Determine the mouse position
            x = e.clientX - parentTh.offsetLeft;
            y = e.clientY - parentTh.offsetTop;

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
            draggingEle.style.top = `${draggingEle.offsetTop + e.clientY - y
            }px`;
            draggingEle.style.left = `${draggingEle.offsetLeft + e.clientX - x
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
            let allDragIcons = table.querySelectorAll(".drag-icon");
            allDragIcons.forEach(dragIcon => {
                dragIcon.addEventListener("mousedown", mouseDownHandler);
            })
        }
    }

    if (selectOnPage !== null && sectionName !== null) {
        var tableUrl = `${location.protocol}//${location.host}${sectionName}?ajax=true&on_page=${selectValue}`;
    }

    // функции обработки кликов
    function paginationClicker(event) {
        event.preventDefault();
        let paginationLinkData = this.getAttribute("href");
        //    console.log('paginationLinkData ', paginationLinkData);
        if (paginationLinkData.includes("ajax=true")) {
            tableUrl = paginationLinkData;
        } else {
            tableUrl = `${paginationLinkData}&ajax=true`;
        }
        getTableData(tableUrl);
    }

    function sortClicker(event) {
        event.preventDefault();
        let hrefData = this.getAttribute("href");
        let hrefDataArr = hrefData.split("&");
        if (hrefDataArr.includes("ajax=true")) {
            tableUrl = hrefData;
        } else {
            tableUrl = `${hrefData}&ajax=true`;
        }
        getTableData(tableUrl);
    }

    function rowsClicker() {
        allRows.forEach((rowDeep) => {
            rowDeep.classList.remove("is-selected");
        });
        this.classList.add("is-selected");
    }

    function rowsDoubleClicker() {
        console.log("rowsDoubleClicker");
        console.log("2 editBtn ", editBtn);
        if (editBtn) {
            editBtn.click();
        }
        body.classList.add("user-editing-panel-opened");
    }

    // при обновлении таблицы надо заново навешивать события на новые элементы
    function setPaginationListeners() {
        paginationLinks = document.querySelectorAll(".page-link");
        if (paginationLinks) {
            paginationLinks.forEach((paginationLink) => {
                paginationLink.addEventListener("click", paginationClicker);
            });
        }
    }

    function setSortListeners() {
        sortIcons = document.querySelectorAll(".sort-icon");
        if (sortIcons) {
            sortIcons.forEach((sortIcon) => {
                sortIcon.addEventListener("click", sortClicker);
            });
        }
    }

    function manageRows() {
        allRows = document.querySelectorAll(".user-table-row");
        if (allRows.length > 0) {
            allRows.forEach((row) => {
                row.addEventListener("click", rowsClicker);
                row.addEventListener("dblclick", rowsDoubleClicker);
            });
        }
    }

    // снимаем слушатели событий, чтобы не перегружать память
    function clearOldListeners() {
        if (sortIcons) {
            sortIcons.forEach((sortIcon) => {
                sortIcon.removeEventListener("click", sortClicker);
            });
        }
        if (paginationLinks) {
            paginationLinks.forEach((paginationLink) => {
                paginationLink.removeEventListener("click", paginationClicker);
            });
        }
        if (allRows) {
            allRows.forEach((row) => {
                row.removeEventListener("click", rowsClicker);
                row.removeEventListener("click", rowsDoubleClicker);
            });
        }
    }

    // получаем данные, заменяем таблицу, пишем запрос в адресную строку, заново вешаем слушатели
    // главная функция обновления таблицы
    async function getTableData(tableUrl) {
        let data = await fetch(tableUrl).then((result) => result.text());
        if (ajaxTable) {
            clearOldListeners();
            ajaxTable.innerHTML = data;
        }
        setSortListeners();
        setPaginationListeners();
        manageRows();

        let urlArr = tableUrl.split("?");
        if (urlArr.length > 1) {
            let urlTail = urlArr[1];
            let urlTailArr = urlTail.split("&");
            //     console.log('urlTailArr ',urlTailArr);
            for (let i = 0; i < urlTailArr.length; i++) {
                if (urlTailArr.includes("ajax=true")) {
                    let ajaxIndex = urlTailArr.indexOf("ajax=true");
                    urlTailArr.splice(ajaxIndex, 1);
                }
            }
            urlTailArr = urlTailArr.join("&");
            let urlString = urlArr[0] + "?" + urlTailArr;
            history.pushState("", "", urlString);
        } else {
            history.pushState("", "", tableUrl);
        }

        initCalendars();
    //    addTableDragNDrop();
    }

    // начало сценария
    if (selectOnPage) {
        selectOnPage.addEventListener("change", function () {
            selectValue = selectOnPage.value;
            tableUrl = `${location.protocol}//${location.host}${sectionName}?on_page=${selectValue}&ajax=true`;
            getTableData(tableUrl);
        });
    }

    if (sortIcons) {
        setSortListeners();
    }

    if (paginationLinks) {
        setPaginationListeners();
    }

    if (allRows) {
        manageRows();
    }

   // addTableDragNDrop();

    // работа с формой Создать

    const btn_form = document.querySelector('button[data-action="send-form"]');
    const addItemAddForm = document.querySelector(".form-create");
    const userEditingPanel = document.querySelector("#userEditingPanel");
    const userEditingOverlay = document.querySelector("#userEditingOverlay");
    const userCreationPanel = document.querySelector("#userCreationPanel");
    const body = document.querySelector("body");

    if (addItemAddForm && btn_form) {
        addItemAddForm.addEventListener("submit", async function (event) {
            event.preventDefault();
            const form_id = btn_form.getAttribute("data-form-id");

            let form_s = document.querySelector("#" + form_id);
            let rq_elem = form_s.querySelectorAll("[required]");
            let error_control = false;
            rq_elem.forEach(function (rq) {
                if (rq.value.length === 0) {
                    error_control = true;
                    rq.classList.add("is-invalid");
                } else {
                    rq.classList.remove("is-invalid");
                }
            });
            if (form_id !== undefined && error_control === false) {
                const sectionName = form_id.split("-")[0];
                let data = new FormData(addItemAddForm);

                // fetch POST
                if (sectionName) {
                    let response = await fetch(`/api/${sectionName}`, {
                        method: "POST",
                        body: data,
                    });
                    let result = await response.json();
                    //console.log(result)
                    if (result.result === "success") {
                        // Ресетим форму после успешной отправки
                        form_s.reset();

                        let panel = document.querySelectorAll(".offcanvas");
                        panel.forEach(function (pn) {
                            pn.classList.remove("show");
                        });
                        let offcanvas = document.querySelectorAll(
                            ".offcanvas-backdrop"
                        );
                        offcanvas.forEach(function (off) {
                            off.classList.remove("show");
                        });

                        console.log("success post!");
                        await getTableData(tableUrl);
                        // находим созданную строку по id и выделяем
                        let rowId = result.id;
                        let activeRow = document.querySelector(
                            `[data-string="${rowId}"]`
                        );
                        if (activeRow) {
                            let firstRow =
                                document.querySelector(".user-table-row");
                            if (firstRow) {
                                firstRow.classList.remove("is-selected");
                            }
                            activeRow.classList.add("is-selected");
                            activeRow.scrollIntoView({
                                block: "center",
                                behavior: "smooth",
                            });
                        }
                    }
                }
            }
        });
    }

    document
        .querySelector("body")
        .addEventListener("submit", async function (e) {
            if (e.target.closest(".form-update")) {
                e.preventDefault();
                let form_s = e.target;

                const form_id = form_s.getAttribute("id");
                let rq_elem = form_s.querySelectorAll("[required]");
                let error_control = false;
                rq_elem.forEach(function (rq) {
                    if (rq.value.length === 0) {
                        error_control = true;
                        rq.classList.add("is-invalid");
                    } else {
                        rq.classList.remove("is-invalid");
                    }
                });
                if (form_id !== undefined && error_control === false) {
                    const sectionName = form_id.split("-")[0];
                    let data = new FormData(form_s);

                    // fetch POST
                    if (sectionName) {
                        let response = await fetch(`/api/${sectionName}`, {
                            method: "POST",
                            body: data,
                        });
                        console.log(data);
                        let result = await response.json();

                        let panel = document.querySelectorAll(".offcanvas");
                        panel.forEach(function (pn) {
                            pn.classList.remove("show");
                        });
                        let offcanvas_upd = document.querySelectorAll(
                            ".offcanvas-backdrop"
                        );
                        offcanvas_upd.forEach(function (off) {
                            off.classList.remove("show");
                        });

                        await getTableData(tableUrl);

                        let rowId = result.id;
                        let activeRow = document.querySelector(
                            `[data-string="${rowId}"]`
                        );
                        if (activeRow) {
                            let firstRow =
                                document.querySelector(".user-table-row");
                            if (firstRow) {
                                firstRow.classList.remove("is-selected");
                            }
                            activeRow.classList.add("is-selected");
                            activeRow.scrollIntoView({
                                block: "center",
                                behavior: "smooth",
                            });
                        }

                        form_s.closest(".offcanvas").classList.remove("show");
                        let offcanvas = document.querySelectorAll(
                            ".offcanvas-backdrop"
                        );
                        offcanvas.forEach(function (off) {
                            off.classList.remove("show");
                        });
                    }
                }
            }
        });
});
