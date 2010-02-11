// JavaScript methods for the Recipe Form for Recipe Press.

function rp_add_ingredient(type) {
    var table = document.getElementById('rp_ingredients_body');
    var rows = table.rows.length;
    var row = table.insertRow(rows);
    row.id = 'rp_ingredient_' + rows;
    row.vAlign = 'top';
    var cellCount = 0;

    // Insert drag cell
    if (type == 'admin') {
        var dragCell = row.insertCell(cellCount);
        dragCell.style.width = '30px';
        controls = document.getElementById('rp_drag_icon').innerHTML;
        dragCell.innerHTML = controls.replace(/NULL/gi, rows).replace(/COPY/gi, '');
        ++cellCount;
    }
    // Insert Quantity Cell
    var quantityCell = row.insertCell(cellCount);
    var quantity = document.createElement('input');
    quantity.type = 'text';
    quantity.name = 'ingredients[' + rows + '][quantity]';
    quantity.style.width = '60px';
    quantityCell.appendChild(quantity);
    ++cellCount;

    // Insert Size Cell
    var sizeCell = row.insertCell(cellCount);
    var contents = document.getElementById('rp_size_column').innerHTML;
    sizeCell.innerHTML = contents.replace(/NULL/gi, rows).replace(/COPY/gi, '');
    ++cellCount;

    // Insert item cell
    var itemCell = row.insertCell(cellCount);
    var item = document.createElement('input');
    item.type = 'text';
    item.name = 'ingredients[' + rows + '][item]';
    itemCell.appendChild(item);
    ++cellCount;

    // Insert notes cell
    var notes = document.createElement('input');
    notes.type = 'text';
    notes.name = 'ingredients[' + rows + '][notes]';

    if (type == 'admin') {
        itemCell.appendChild(notes);
    } else {
        var notesCell = row.insertCell(cellCount);
        notesCell.appendChild(notes);
        ++cellCount;
    }
    
    // Insert Page Cell
    if (type == 'admin') {
        var pageCell = row.insertCell(cellCount);
        contents = document.getElementById('rp_page_column').innerHTML;
        pageCell.innerHTML = contents.replace(/NULL/gi, rows);
    }

    table.appendChild(row);
}

function rp_add_divider(type) {
    var table = document.getElementById('rp_ingredients_body');
    var rows = table.rows.length;
    var row = table.insertRow(rows);
    row.id = 'rp_ingredient_' + rows;
    row.className = 'rp_size_type_divider';
    row.vAlign = 'top';
    var cellCount = 0;

    // Insert drag cell
    if (type == 'admin') {
        var dragCell = row.insertCell(cellCount);
        dragCell.style.width = '30px';
        controls = document.getElementById('rp_drag_icon').innerHTML;
        dragCell.innerHTML = controls.replace(/NULL/gi, rows).replace(/COPY/gi, '');
        ++cellCount;
    }

    // Insert Quantity Cell
    var quantityCell = row.insertCell(cellCount);
    quantityCell.style.width = '60px';
    ++cellCount;

    // Insert Size Cell
    var sizeCell = row.insertCell(cellCount);
    var item = document.createElement('input');
    item.type = 'text';
    item.name = 'ingredients[' + rows + '][size]';
    item.value = 'divider';
    item.style.width = '55px';
    item.readOnly = true;
    sizeCell.appendChild(item);
    ++cellCount;

    // Insert Item cell
    var itemCell = row.insertCell(cellCount);
    item = document.createElement('input');
    item.type = 'text';
    item.name = 'ingredients[' + rows + '][item]';
    itemCell.appendChild(item);
    ++cellCount;

    // Insert Page Cell
    if (type == 'admin') {
        var pageCell = row.insertCell(cellCount);
    }

    table.appendChild(row);
}

function rp_delete_row(elem) {
    if (confirm('You are about to delete this ingredient row, are you sure?') ) {
        var tableRow = document.getElementById(elem);
        var row = tableRow.rowIndex - 1;
        document.getElementById('rp_ingredients_body').deleteRow(row);
    }
}

