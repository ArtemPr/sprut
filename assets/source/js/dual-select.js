/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */
document.addEventListener("DOMContentLoaded", function(event) {

    let managingDepartmentsModal = document.querySelector('[data-bs-target="#managingDepartmentsModal"]');
   // console.log('managingModalsBtn ', managingDepartmentsModal);

    if(managingDepartmentsModal) {
        managingDepartmentsModal.addEventListener('click', function(){
            console.log('click managingDepartmentsModal');
            // setTimeout(() => {
            //
            // }, 1000);
            const dualItems = document.querySelectorAll('.dual-listbox__item');
            const leftPanel = document.querySelector('.dual-controls-select');
            const rightPanel = document.querySelector('.dual-listbox__selected');
            console.log('dualItems ', dualItems);

            const addOneBtn = document.querySelector('[name="add_one"]');
            const addAllBtn = document.querySelector('[name="add_all"]');
            const removeOneBtn = document.querySelector('[name="remove_one"]');
            const removeAllBtn = document.querySelector('[name="remove_all"]');

            function addOne(){
                console.log('addOne')
                leftPanel.removeChild(this);
                rightPanel.appendChild(this);
            }

            // function toggleSelected(){
            //     dualItems.forEach(dualItem=>dualItem.classList.remove('dual-listbox__item--selected'));
            //     this.classList.toggle('dual-listbox__item--selected');
            // }

            if(dualItems && leftPanel && leftPanel) {
                dualItems.forEach(dualItem=>dualItem.addEventListener('dblclick', addOne));
             //   dualItems.forEach(dualItem=>dualItem.addEventListener('click', toggleSelected));
            }

            if(addOneBtn && leftPanel && leftPanel && dualItems) {
                addOneBtn.addEventListener('click', addOne)
            }



        })
}
    })

