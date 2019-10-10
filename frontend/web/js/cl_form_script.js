"use strict";

function addFields(item = null) {

    let b = $("#create_form input[name=items]").length + 1;
    let max = document.getElementById("vars").dataset.max;
    if (b <= max) {
        let container = document.getElementById("container");
        let input = document.createElement("input");
        input.type = "text";
        input.name = "items";
        input.class = "cl_item";
        if (item != null) {
            input.value = item.name;
            input.dataset.id = item.id;
        }
        container.appendChild(document.createTextNode("Item " + b));
        container.appendChild(input);
        container.appendChild(document.createElement("br"));
    }
}


function InitForm () {

    $(".active-element").off();

    document.getElementsByClassName('modal-header')[0].innerHTML = '<h3>Add checklist</h3>';


    try {
        let items = window.JSON.parse(document.getElementById("vars").dataset.items);
        if (items != null) {
            items.forEach(function (element) {
                addFields(element);
            });
        }
    }catch (e) {
        
    }
       

    $(".add-fields").click(function () {
        addFields();
    });

    $(".remove-fields").click(function () {
        let container = document.getElementById("container");
        container.removeChild(container.lastChild);
        container.removeChild(container.lastChild);
        container.removeChild(container.lastChild);


    });

    $(".submit-form").click(function () {

        let data = {};
        let form_items = $("#create_form input[name=items]");
        let form_name = $("#create_form input[name=name]")[0];
        let cl_id = document.getElementById("vars").dataset.clId;
        data.name = form_name.value;
        data.items = [];
        Array.from(form_items).forEach(function (value, index, array) {
            if (value.dataset.id != null) {
                data.items.push(
                    {
                        item_name: value.value,
                        item_id: value.dataset.id
                    });
            } else {
                data.items.push(
                    {
                        item_name: value.value,
                    });
            }
        });
        data[document.getElementById("vars").dataset.csrf] = document.getElementById("vars").dataset.token;

        if (cl_id != null) {
            $.post("/user/checklist-form?upd_id=" + cl_id, data, function () {
                $.pjax.reload({container: "#grid_view", timeout: false});
            });
        } else {
            $.post("/user/checklist-form", data, function () {
                $.pjax.reload({container: "#grid_view", timeout: false});
            });
        }
    });
}







