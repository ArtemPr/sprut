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

    function chooseFgos(){
        
    }

        const fgosTypeSelect = document.querySelector('.fgos-type-select');
        const fgosEducation = document.querySelector('.fgos-education');

        if (fgosTypeSelect && fgosEducation) {
            // выбираем тип образования
            fgosTypeSelect.addEventListener('change', async function () {
                const value = fgosTypeSelect.options[fgosTypeSelect.selectedIndex].value;
                if (value === '1') {
                    fgosEducation.classList.remove('fgos-education--show');
                } else {
                    fgosEducation.classList.add('fgos-education--show');
                    const fgosFilter = document.querySelector('.fgos-filter');
                    const fgosHiddenInput = document.querySelector('.fgos-hidden');
                    const fgosEducationList = document.querySelector('.fgos-education__list');
                    let fgosEducationUrl = `${location.protocol}//${location.host}/tmp/${value}.json`;
                    // получаем файл по ajax
                    let response = await fetch(fgosEducationUrl);
                    let result = await response.json();
                    // console.log(result);

                    // поля для таблицы и массив для фильтрации
                    let tableLis = '';
                 //   let resultArr = [];
                    if (result.length > 0) {
                        result.forEach(item=>{
                            tableLis += `<li data-id="${item.id}" class="fgos-education__item">${item.value}</li>`;
                        //    resultArr.push(item.value);
                        })
                        fgosEducationList.innerHTML=tableLis;
                        let liItems = document.querySelectorAll('.fgos-education__item');

                        if(liItems.length > 0) {
                            liItems.forEach(liItem => {
                                liItem.addEventListener('click', function(){
                                    fgosFilter.value = liItem.textContent;
                                    fgosHiddenInput.value = liItem.getAttribute('data-id');
                                    console.log('gosHiddenInput.value ', fgosHiddenInput.value);
                                })
                            })
                        }
                    }

                    if(fgosFilter){
                        fgosFilter.addEventListener('input', function(){
                            let fgosFilterValue = fgosFilter.value;
                            // фильтруем массив по содержимому инпута
                            function filterItems(fgosFilterValue) {
                                // return resultArr.filter(function(el) {
                                //     return el.toLowerCase().indexOf(fgosFilterValue.toLowerCase()) > -1;
                                // })

                                let filtered =  result.filter(function(fgosFilterValue) {
                                    return result.value.toLowerCase().indexOf(fgosFilterValue.toLowerCase()) > -1;
                                });
                                console.log(filtered);
                            }
                            let filteredItems = filterItems(fgosFilterValue);
                            // заново набиваем таблицу
                            if(filteredItems.length > 0) {
                                tableLis = '';
                                filteredItems.forEach(
                                    item=>{
                                        tableLis += `<li data-id="${item.id}" class="fgos-education__item">${item.value}</li>`;
                                    }
                                )
                                fgosEducationList.innerHTML=tableLis;
                                let liItems = document.querySelectorAll('.fgos-education__item');
                                liItems.forEach(liItem => {
                                    liItem.addEventListener('click', function(){
                                        fgosFilter.value = liItem.textContent;
                                        fgosHiddenInput.value = liItem.getAttribute('data-id');
                                        console.log('gosHiddenInput.value ', fgosHiddenInput.value);
                                    })
                                })
                            } else {
                                fgosEducationList.innerHTML = '<p>Ничего не найдено</p>';
                            }
                        })
                    }
                }
            })
        }
    });
