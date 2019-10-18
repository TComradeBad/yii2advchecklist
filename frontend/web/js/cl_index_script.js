"use strict"

function searchAction() {;
    let post_data = {search: $("#search_cl_input").val()};
    $.pjax.reload(
        {
            container: "#grid_view",
            timeout: false,
            url: "/user/checklists?search=go",
            type: "POST",
            data: post_data
        }
    );
}
$("#modal").on("hide.bs.modal",function () {
    $("#modalContent").html("");
});