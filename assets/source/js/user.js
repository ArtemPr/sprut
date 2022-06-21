'use strict';

const body = document.querySelector('body');
const userTableRows = document.querySelectorAll('.user-table-row');
const userEditingPanel = document.querySelector('#userEditingPanel');
const userEditingPanelCloser = document.querySelector('#jsEditingPanelCloser');
const userEditingOverlay = document.querySelector('#userEditingOverlay');

/**
 * Users Interaction
 */

for (let i = 0; i < userTableRows.length; ++i) {
  let userItem = userTableRows[i];

  userItem.addEventListener('click', function () {
    userTableRows.forEach(f => f.classList.remove('is-selected'));
    userItem.classList.add('is-selected');

    userTableRows.forEach(f => f.querySelector('.selected-checkbox').checked = false);
    userItem.querySelector('.selected-checkbox').checked = true;
  });

  userItem.addEventListener('dblclick', function () {
    body.classList.add('user-editing-panel-opened');
    userEditingPanel.classList.add('show');
    userEditingOverlay.classList.add('show');
  });

}

if (userEditingPanelCloser !== undefined && userEditingPanelCloser !== null) {
        userEditingPanelCloser.addEventListener('click', function () {
            body.classList.remove('user-editing-panel-opened');
            userEditingPanel.classList.remove('show');
            userEditingOverlay.classList.remove('show');
        });
}

if(userEditingOverlay !== undefined && userEditingOverlay !== null) {
    userEditingOverlay.addEventListener('click', function () {
        body.classList.remove('user-editing-panel-opened');
        userEditingPanel.classList.remove('show');
        userEditingOverlay.classList.remove('show');
    });
}




/**
 * Editable Table
 */

const editableTables = document.querySelectorAll('.editable-table');
const customAsidePanels = document.querySelectorAll('.custom-aside-panel');
const customOverlay = document.querySelector('.custom-overlay');
const customPanelCloser = document.querySelector('.custom-panel-closer');
for (let i = 0; i < editableTables.length; ++i) {
  let editableTable = editableTables[i];
  let childEditableRows = editableTable.querySelectorAll('.editable-table-row');

  for (let i = 0; i < childEditableRows.length; ++i) {
    let editableTableRow = childEditableRows[i];
    editableTableRow.addEventListener('click', function () {
      childEditableRows.forEach(f => f.classList.remove('is-selected'));
      editableTableRow.classList.add('is-selected');

      childEditableRows.forEach(f => f.querySelector('.selected-checkbox').checked = false);
      editableTableRow.querySelector('.selected-checkbox').checked = true;
    });

    editableTableRow.addEventListener('dblclick', function () {
      let targetPanel = document.querySelector(this.getAttribute('data-target-panel'));
      let targetOverlay = document.querySelector(this.getAttribute('data-target-overlay'));
      if (targetPanel !== null && targetOverlay !== null) {
        body.classList.add('user-editing-panel-opened');
        targetPanel.classList.add('show');
        targetOverlay.classList.add('show');
      }
    });

  }

}
if (customPanelCloser != null) {
  customPanelCloser.addEventListener('click', function () {
    body.classList.remove('user-editing-panel-opened');
    this.closest('.custom-aside-panel').classList.remove('show');
    customOverlay.classList.remove('show');
  });
}
if (customOverlay != null) {
  customOverlay.addEventListener('click', function () {
    body.classList.remove('user-editing-panel-opened');
    customAsidePanels.forEach(f => f.classList.remove('show'));
    this.classList.remove('show');
  });
}


/**
 * User Photo Upload
 */

window.addEventListener('load', function() {
  const userPhotoInputs = document.querySelectorAll('.user-photo-input');
  for (let i = 0; i < userPhotoInputs.length; ++i) {
    let userPhotoInput = userPhotoInputs[i];
    userPhotoInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const thisParent = this.parentElement;
        const thisImg = thisParent.querySelector('.user-photo-img');
        thisImg.onload = () => {
          URL.revokeObjectURL(thisImg.src);
        }
        thisImg.src = URL.createObjectURL(this.files[0]);
      }
    });
  }
});



/**
 * User Dual Controls
 */

const dualControlsSelects = document.querySelectorAll('.dual-controls-select');
for (let i = 0; i < dualControlsSelects.length; ++i) {
  let dualControlsSelect = dualControlsSelects[i];
  new DualListbox(dualControlsSelect, {
    availableTitle: 'Available numbers',
    selectedTitle: 'Selected numbers',
    addButtonText: '>',
    removeButtonText: '<',
    addAllButtonText: '>>',
    removeAllButtonText: '<<',
    searchPlaceholder: 'Поиск'
  });
}


/**
 * Search Clear
 */


/*используется шаблон templates/administrator/user/block/search_panel.html.twig
руками задан id инпуту и кнопочке id jsClearSearch, похоже, что начальное решение было
изящным, но что-то пошло не так, поэтому правка */
window.addEventListener('load', function() {
  const clearSearchBtn = document.querySelector('#jsClearSearch');
  const searchInput = document.querySelector('#input-search');
  console.log('jsClearSearch');
  if(clearSearchBtn !== undefined && clearSearchBtn !== null) {
      clearSearchBtn.addEventListener('click', function (e) {
          e.preventDefault();
          // const closestForm = clearSearchBtn.closest("form");
          // console.log(closestForm);
          // const thisSearchInput = closestForm.querySelector('.search-input');
          // thisSearchInput.value = "";
          if(searchInput) {
              searchInput.value='';
          }
      });
  }
});



/**
 * Users Table Drag'n'Drop
 */

document.addEventListener('DOMContentLoaded', function () {
  const table = document.getElementById('usersTable');

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
    const siblingA = nodeA.nextSibling === nodeB ? nodeA : nodeA.nextSibling;

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

    list = document.createElement('div');
    list.classList.add('t-clone-list');
    list.style.position = 'absolute';
    list.style.left = `${rect.left}px`;
    list.style.top = `${rect.top}px`;
    table.parentNode.insertBefore(list, table);

    // Hide the original table
    table.style.visibility = 'hidden';

    // Get all cells
    const originalCells = [].slice.call(table.querySelectorAll('tbody td'));

    const originalHeaderCells = [].slice.call(table.querySelectorAll('th'));
    const numColumns = originalHeaderCells.length;

    // Loop through the header cells
    originalHeaderCells.forEach(function (headerCell, headerIndex) {
      const width = parseInt(window.getComputedStyle(headerCell).width);

      // Create a new table from given row
      const item = document.createElement('div');
      item.classList.add('t-draggable');

      const newTable = document.createElement('table');
      newTable.setAttribute('class', 't-clone-table');
      newTable.style.width = `${width}px`;

      // Header
      const th = headerCell.cloneNode(true);
      let newRow = document.createElement('tr');
      newRow.appendChild(th);
      newTable.appendChild(newRow);

      const cells = originalCells.filter(function (c, idx) {
        return (idx - headerIndex) % numColumns === 0;
      });
      cells.forEach(function (cell) {
        const newCell = cell.cloneNode(true);
        newCell.style.width = `${width}px`;
        newRow = document.createElement('tr');
        newRow.appendChild(newCell);
        newTable.appendChild(newRow);
      });

      item.appendChild(newTable);
      list.appendChild(item);
    });
  };

  const mouseDownHandler = function (e) {

    if ( e.button !== 0 || (e.target.classList[0] === 'sort-icon') ) return false;

    draggingColumnIndex = [].slice.call(table.querySelectorAll('th')).indexOf(e.target);

    // Determine the mouse position
    x = e.clientX - e.target.offsetLeft;
    y = e.clientY - e.target.offsetTop;

    // Attach the listeners to `document`
    document.addEventListener('mousemove', mouseMoveHandler);
    document.addEventListener('mouseup', mouseUpHandler);
  };

  const mouseMoveHandler = function (e) {
    if (e.button !== 0) return false;

    if (!isDraggingStarted) {
      isDraggingStarted = true;

      cloneTable();

      draggingEle = [].slice.call(list.children)[draggingColumnIndex];
      draggingEle.classList.add('t-dragging');

      // Let the placeholder take the height of dragging element
      // So the next element won't move to the left or right
      // to fill the dragging element space
      placeholder = document.createElement('div');
      placeholder.classList.add('t-placeholder');
      draggingEle.parentNode.insertBefore(placeholder, draggingEle.nextSibling);
      placeholder.style.width = `${draggingEle.offsetWidth}px`;
    }

    // Set position for dragging element
    draggingEle.style.position = 'absolute';
    draggingEle.style.top = `${draggingEle.offsetTop + e.clientY - y}px`;
    draggingEle.style.left = `${draggingEle.offsetLeft + e.clientX - x}px`;

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
    // // Remove the placeholder
    placeholder && placeholder.parentNode.removeChild(placeholder);

    draggingEle.classList.remove('t-dragging');
    draggingEle.style.removeProperty('top');
    draggingEle.style.removeProperty('left');
    draggingEle.style.removeProperty('position');

    // Get the end index
    const endColumnIndex = [].slice.call(list.children).indexOf(draggingEle);

    isDraggingStarted = false;

    // Remove the `list` element
    list.parentNode.removeChild(list);

    // Move the dragged column to `endColumnIndex`
      if(table !== undefined && table !== null) {
          table.querySelectorAll('tr').forEach(function (row) {
              const cells = [].slice.call(row.querySelectorAll('th, td'));
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
          table.style.removeProperty('visibility');
      }




    // Remove the handlers of `mousemove` and `mouseup`
    document.removeEventListener('mousemove', mouseMoveHandler);
    document.removeEventListener('mouseup', mouseUpHandler);
  };

    if(table !== undefined && table !== null) {
        table.querySelectorAll('th').forEach(function (headerCell) {
            headerCell.classList.add('t-draggable');
            headerCell.addEventListener('mousedown', mouseDownHandler);
        });
    }

});


/* JQuery START */

if (window.jQuery) {

  $(function() {

    /**
     * JS Tree Init
     */
    const $editableTree = $('.editable-tree');
    $editableTree.jstree({
      "core": {
        "animation": 0,
        "check_callback" : true,
        'themes' : {
          'responsive' : true,
          'stripes' : false
        }
      },
      "types" : {
        "#" : {
          "max_children": 1,
          "max_depth": 2
        }
      },
      'plugins': ['state','dnd','sort','types','contextmenu','unique'],
      "contextmenu": {
        "items": function($node) {
          let tree = $editableTree.jstree(true);
          return {
            "Create": false,
            "Rename": {
              "label": "Изменить",
              "action": function (obj) {
                tree.edit($node);
              }
            },
            "Remove": {
              "label": "Удалить",
              "action": function (obj) {
                tree.delete_node($node);
              }
            }
          };
        }
      }
    });
    $(document).on('click', '.js-create-node-tree', function () {
      let ref = $editableTree.jstree(true);
      let sel = ref.get_selected();
      if(!sel.length) { return false; }
      sel = sel[0];
      sel = ref.create_node(sel, {"type":"file"});
      if(sel) {
        ref.edit(sel);
      }
    });
    $(document).on('click', '.js-edit-node-tree', function () {
      let ref = $editableTree.jstree(true);
      let sel = ref.get_selected();
      if(!sel.length) { return false; }
      sel = sel[0];
      ref.edit(sel);
    });
    $(document).on('click', '.js-remove-node-tree', function () {
      let ref = $editableTree.jstree(true);
      let sel = ref.get_selected();
      if(!sel.length) { return false; }
      ref.delete_node(sel);
    });

  });

}



/* JQuery END */
