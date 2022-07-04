/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

document.addEventListener("DOMContentLoaded", function (event) {
        // для заготовки datalist из tabler
        // const addFgosBtn = document.querySelector("#add-fgos");
        // const fgosItem = document.querySelector(".fgos-item");
        // let datalistId = 2;
        //
        // if (addFgosBtn && fgosItem) {
        //     addFgosBtn.addEventListener("click", createFgosItem);
        // }
        //
        // function createFgosItem() {
        //     event.preventDefault();
        //     event.stopPropagation(); // внезапно закрывал форму
        //     //    console.log('addFgosBtn click');
        //     let fgosClone = fgosItem.cloneNode(true);
        //     let input = fgosClone.querySelector(".form-control");
        //     let datalist = fgosClone.querySelector(".fgos-datalist");
        //     // console.log(input);
        //     // console.log(datalist);
        //     input.setAttribute("list", `datalistOptions${datalistId}`);
        //     datalist.setAttribute("id", `datalistOptions${datalistId}`);
        //     //    let cloneAddBtn = fgosClone.querySelector('.add-fgos');
        //     //    cloneAddBtn.addEventListener('click', createFgosItem);
        //     let allFgosItems = document.querySelectorAll(".fgos-item");
        //     if (allFgosItems.length > 1) {
        //         allFgosItems[allFgosItems.length - 1].after(fgosClone);
        //     } else {
        //         fgosItem.after(fgosClone);
        //     }
        //     datalistId++;
        // }
        // для заготовки datalist из tabler

        const fgosTypeSelect = document.querySelector('.fgos-type-select');
        const fgosEducation = document.querySelector('.fgos-education');

        if (fgosTypeSelect && fgosEducation) {
            fgosTypeSelect.addEventListener('change', async function () {
            //    console.log('fgosTypeSelect change');
                const value = fgosTypeSelect.options[fgosTypeSelect.selectedIndex].value;
            //    console.log('value ', value);
                if (value === '1') {
                    fgosEducation.classList.remove('fgos-education--show');
                } else {
                    const fgosFilter = document.querySelector('.fgos-filter');
                    fgosEducation.classList.add('fgos-education--show');
                    const fgosEducationList = document.querySelector('.fgos-education__list');
                    let fgosEducationUrl = `${location.protocol}//${location.host}/tmp/${value}.json`;
                 //   console.log(fgosEducationUrl);
                    let response = await fetch(fgosEducationUrl);
                    let result = await response.json();
                //    console.log(result);
                    let tableLis = '';
                    let resultArr = [];
                    result.forEach(item=>{
                        tableLis += `<li class="fgos-education__item">${item.value}</li>`;
                        resultArr.push(item.value);
                    })
                    fgosEducationList.innerHTML=tableLis;

                    let liItems = document.querySelectorAll('.fgos-education__item');
                    console.log('liItems ', liItems);
                    liItems.forEach(liItem => {
                        liItem.addEventListener('click', function(){
                            console.log('1 liItem textContent ', liItem.textContent);
                            fgosFilter.value = liItem.textContent;
                        })
                    })


                    fgosFilter.addEventListener('input', function(){
                        let fgosFilterValue = fgosFilter.value;
                        console.log('fgosFilterValue ', fgosFilterValue);

                        function filterItems(fgosFilterValue) {
                            return resultArr.filter(function(el) {
                                return el.toLowerCase().indexOf(fgosFilterValue.toLowerCase()) > -1;
                            })
                        }

                        let filteredItems = filterItems(fgosFilterValue);

                        if(filteredItems.length > 0) {
                            tableLis = '';
                            filteredItems.forEach(
                                item=>{
                                    tableLis += `<li class="fgos-education__item">${item}</li>`;
                                }
                            )
                            fgosEducationList.innerHTML=tableLis;

                            let liItems = document.querySelectorAll('.fgos-education__item');
                            console.log('liItems ', liItems);
                            liItems.forEach(liItem => {
                                liItem.addEventListener('click', function(){
                                    console.log('2 liItem textContent ', liItem.textContent);
                                    fgosFilter.value = liItem.textContent;
                                })
                            })
                        } else {
                            fgosEducationList.innerHTML = '<p>Ничего не найдено</p>';
                        }
                    })
                }
            })
        }
    });
