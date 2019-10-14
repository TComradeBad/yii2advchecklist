"use strict"

function InitClItems() {
    $("#my_cl_grid").find(".pagination li a").click(function () {
        $("#modalContent").load($(this).attr('href'));
        return false;
    });
}

