/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

document.addEventListener("DOMContentLoaded", function (event) {
    let fgosBtns = document.querySelectorAll("[data-controller='program']");
    let fgosBtnCreate = null;
    let fgosBtnUpdate = null;
    if (fgosBtns) {
        fgosBtns.forEach(fgosBtn => {
            let action = fgosBtn.getAttribute('data-action');
            if (action === 'add') {
                fgosBtnCreate = fgosBtn;
            } else if (action === 'edit') {
                fgosBtnUpdate = fgosBtn;
            }
        })
    }

    if (fgosBtnCreate !== null) {
        fgosBtnCreate.addEventListener('click', function () {
            setTimeout(() => {
                let fgosContainer = document.querySelector('.fgos-tab--add');
                if (fgosContainer) {
                    chooseFgos(fgosContainer);
                }
            }, 1000)
        })
    }

    if (fgosBtnUpdate !== null) {
        fgosBtnUpdate.addEventListener('click', function () {
            setTimeout(() => {
                let fgosContainer = document.querySelector('.fgos-tab--edit');
                if (fgosContainer) {
                    chooseFgos(fgosContainer);
                }
            }, 1000)
        })
    }

    function chooseFgos(fgosContainer) {
        const fgosTypeSelect = fgosContainer.querySelector('.fgos-type-select');
        const fgosEducation = fgosContainer.querySelector('.fgos-education');

        if (fgosTypeSelect && fgosEducation) {
            // выбираем тип образования
            fgosTypeSelect.addEventListener('change', async function () {
                const value = fgosTypeSelect.options[fgosTypeSelect.selectedIndex].value;
                if (value === '1') {
                    fgosEducation.classList.remove('fgos-education--show');
                } else {
                    fgosEducation.classList.add('fgos-education--show');
                    const fgosFilter = fgosContainer.querySelector('.fgos-filter');
                    const fgosHiddenInput = fgosContainer.querySelector('.fgos-hidden');
                    const fgosEducationList = fgosContainer.querySelector('.fgos-education__list');
                    let fgosEducationUrl = `${location.protocol}//${location.host}/tmp/${value}.json`;
                    // получаем файл по ajax
                    let response = await fetch(fgosEducationUrl);
                    let result = await response.json();
                    // console.log(result);

                    // поля для таблицы
                    let tableLis = '';
                    if (result.length > 0) {
                        result.forEach(item => {
                            tableLis += `<li data-id="${item.id}" class="fgos-education__item">${item.value}</li>`;
                        })
                        fgosEducationList.innerHTML = tableLis;
                        let liItems = fgosContainer.querySelectorAll('.fgos-education__item');

                        if (liItems.length > 0) {
                            liItems.forEach(liItem => {
                                liItem.addEventListener('click', function () {
                                    fgosFilter.value = liItem.textContent;
                                    fgosHiddenInput.value = liItem.getAttribute('data-id');
                                    console.log('fgosHiddenInput.value ', fgosHiddenInput.value);
                                    console.log('fgosFilter.value ', fgosFilter.value);
                                })
                            })
                        }
                    }

                    // фильтруем массив объектов
                    if (fgosFilter) {
                        fgosFilter.addEventListener('input', function () {
                            let fgosFilterValue = fgosFilter.value;
                            let filtered = result.filter(function (resultItem) {
                                return resultItem.value.toLowerCase().indexOf(fgosFilterValue.toLowerCase()) > -1;
                            });
                            //   console.log('filtered ', filtered);

                            // заново набиваем таблицу отфильтрованными
                            if (filtered.length > 0) {
                                tableLis = '';
                                filtered.forEach(
                                    item => {
                                        tableLis += `<li data-id="${item.id}" class="fgos-education__item">${item.value}</li>`;
                                    }
                                )
                                fgosEducationList.innerHTML = tableLis;
                                let liItems = fgosContainer.querySelectorAll('.fgos-education__item');
                                liItems.forEach(liItem => {
                                    liItem.addEventListener('click', function () {
                                        fgosFilter.value = liItem.textContent;
                                        fgosHiddenInput.value = liItem.getAttribute('data-id');
                                        console.log('fgosHiddenInput.value ', fgosHiddenInput.value);
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
    }
});
