"use strict";

function InitAdminClItems() {

    $("#user_cl_items").find(".pagination li a").off();
    $("#user_cl_items").find(".pagination li a").click(function () {
        $("#modalContent").load($(this).attr('href'));
        return false;
    });

    $("#soft_delete_button").off();
    $("#soft_delete_button").click(function () {
        if ($("#soft_delete_button")[0].dataset.status === "true") {
            $("#soft_delete_button")[0].dataset.status = "false";
        } else {
            $("#soft_delete_button")[0].dataset.status = "true";
        }


        if ($("#soft_delete_button")[0].dataset.status === "true") {
            $.ajax({
                url: $(this).attr('value'),
                async: true,
                type: "POST",
                cache: false,
                global: false,
                success: function (html) {
                    $('#soft_delete_form').html(html);
                }
            });
        } else {
            $('#soft_delete_form').html("");
        }

    });

    $("#unset_sd_button").off();
    $("#unset_sd_button").click(function () {

        let xrf = new XMLHttpRequest();
        let data = new FormData();
        let id = document.getElementById("vars").dataset.clId;
        data.set(document.getElementById("vars").dataset.csrf, document.getElementById("vars").dataset.token);
        data.set("cl_id", id);
        xrf.open("post", $(this).val());
        xrf.send(data);

        xrf.onload = function () {
            let url = window.location.href;
            let user_id = document.getElementById("vars").dataset.userId;
            let id = document.getElementById("vars").dataset.clId;
            if (xrf.status == "200") {
                $.pjax.reload({container: "#grid_view"});
                $("#modalContent").load("/admin/view-user-info?id=" + user_id + "&cl_id=" + id);

            }
        }


    });

}




