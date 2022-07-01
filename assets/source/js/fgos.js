/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

document.addEventListener("DOMContentLoaded", function (event) {
    const addFgosBtn = document.querySelector("#add-fgos");
    const fgosItem = document.querySelector(".fgos-item");
    let datalistId = 2;

    if (addFgosBtn && fgosItem) {
        addFgosBtn.addEventListener("click", createFgosItem);
    }

    function createFgosItem() {
        event.preventDefault();
        event.stopPropagation(); // внезапно закрывал форму
        //    console.log('addFgosBtn click');
        let fgosClone = fgosItem.cloneNode(true);
        let input = fgosClone.querySelector(".form-control");
        let datalist = fgosClone.querySelector(".fgos-datalist");
        // console.log(input);
        // console.log(datalist);
        input.setAttribute("list", `datalistOptions${datalistId}`);
        datalist.setAttribute("id", `datalistOptions${datalistId}`);
        //    let cloneAddBtn = fgosClone.querySelector('.add-fgos');
        //    cloneAddBtn.addEventListener('click', createFgosItem);
        let allFgosItems = document.querySelectorAll(".fgos-item");
        if (allFgosItems.length > 1) {
            allFgosItems[allFgosItems.length - 1].after(fgosClone);
        } else {
            fgosItem.after(fgosClone);
        }
        datalistId++;
    }
});
