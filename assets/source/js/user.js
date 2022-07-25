"use strict";

// получаем из npm пакетов
require('imask');

document.addEventListener("DOMContentLoaded", function(event) {
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
        if (addTel) {
            let addMaskOptions = {
                mask: '+{7}(000)000-00-00'
            };
            let addMask = IMask(addTel, addMaskOptions);
        }

        if (editUserBtn !== null) {
            editUserBtn.addEventListener('click', function () {
                let interval_update = setInterval(function (){
                    let editTel = document.querySelector(".form-update [name='phone']");
                    if(editTel) {
                        clearInterval(interval_update);
                        let editMaskOptions = {
                            mask: '+{7}(000)000-00-00'
                        };
                        let editMask = IMask(editTel, editMaskOptions);
                    }
                }, 2000);
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

    // const dualControlsSelects = document.querySelectorAll(".dual-controls-select_create");
    // for (let i = 0; i < dualControlsSelects.length; ++i) {
    //     let dualControlsSelect = dualControlsSelects[i];
    //     new DualListbox(dualControlsSelect, {
    //         availableTitle: "Available numbers",
    //         selectedTitle: "Selected numbers",
    //         addButtonText: ">",
    //         removeButtonText: "<",
    //         addAllButtonText: ">>",
    //         removeAllButtonText: "<<",
    //         searchPlaceholder: "Поиск",
    //     });
    // }

    /**
     * Search Clear
     */

    window.addEventListener("load", function () {
        const clearSearchBtn = document.querySelector("#jsClearSearch");
        const searchInput = document.querySelector("#input-search");
        if (clearSearchBtn !== undefined && clearSearchBtn !== null) {
            clearSearchBtn.addEventListener("click", function (e) {
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

})





