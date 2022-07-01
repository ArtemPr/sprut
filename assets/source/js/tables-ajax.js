/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

// изменение данных в таблице при GET параметре ajax=true
document.addEventListener("DOMContentLoaded", function (event) {
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
  //  console.log("1 editBtn ", editBtn);

    if (selectOnPage !== null && sectionName !== null) {
        var tableUrl = `${location.protocol}//${location.host}${sectionName}?ajax=true&&on_page=${selectValue}`;
    }
    console.log(tableUrl);

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
        //userEditingPanel.classList.add("show");
        //userEditingOverlay.classList.add("show");
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
    async function getTableData(tableUrl) {
        //    console.log('tableUrl ', tableUrl);
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
            //    console.log('scary url');
            history.pushState("", "", urlString);
        } else {
            history.pushState("", "", tableUrl);
        }
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
                form_s = e.target;

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
