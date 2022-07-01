/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */
// временно элементов нет
// document.addEventListener("DOMContentLoaded", function (event) {
//     const managingDepartmentsModals = document.querySelectorAll(
//         '[data-bs-target="#managingDepartmentsModal"]'
//     );
//
//     //console.log(managingDepartmentsModal.textContent);
//
//     const dualItems = document.querySelectorAll(".dual-listbox__item");
//     const leftPanel = document.querySelector(".dual-controls-select");
//     const rightPanel = document.querySelector(".dual-listbox__selected");
//     const addOneBtn = document.querySelector('[name="add_one"]');
//     const addAllBtn = document.querySelector('[name="add_all"]');
//     const removeOneBtn = document.querySelector('[name="remove_one"]');
//     const removeAllBtn = document.querySelector('[name="remove_all"]');
//
//     console.log(addOneBtn);
//
//     if (addOneBtn && leftPanel && rightPanel && dualItems) {
//         addOneBtn.addEventListener("click", addOne);
//     }
//
//     function addOne() {
//         console.log("addOne");
//         leftPanel.removeChild(this);
//         rightPanel.appendChild(this);
//     }
//
//     function toggleSelected() {
//         dualItems.forEach((dualItem) =>
//             dualItem.classList.remove("dual-listbox__item--selected")
//         );
//         this.classList.toggle("dual-listbox__item--selected");
//     }
//
//     dualItems.forEach((dualItem) => {
//         dualItem.addEventListener("click", toggleSelected);
//     });
//
//     // if (managingDepartmentsModals) {
//     //     managingDepartmentsModals.forEach((modalBtn) => {
//     //         modalBtn.addEventListener("click", function () {
//     //             console.log("click managingDepartmentsModal");
//     //         });
//     //     });
//     //     //  console.log('managingDepartmentsModal is')
//     //     managingDepartmentsModal.addEventListener("click", function () {
//     //         console.log("click managingDepartmentsModal");
//     //         // if (addOneBtn && leftPanel && rightPanel && dualItems) {
//     //         //     console.log('all, add f')
//     //         //     addOneBtn.addEventListener("click", addOne);
//     //         // }
//
//     //         // function toggleSelected(){
//     //         //     dualItems.forEach(dualItem=>dualItem.classList.remove('dual-listbox__item--selected'));
//     //         //     this.classList.toggle('dual-listbox__item--selected');
//     //         // }
//
//     //         // if (dualItems && leftPanel && rightPanel) {
//     //         //     dualItems.forEach((dualItem) =>
//     //         //         dualItem.addEventListener("dblclick", addOne)
//     //         //     );
//     //         //     //   dualItems.forEach(dualItem=>dualItem.addEventListener('click', toggleSelected));
//     //     });
//     //     //   });
//     // }
// });
