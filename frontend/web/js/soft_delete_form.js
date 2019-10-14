"use strict"

function InitSoftDeleteForm() {

    $("#problem_submit").off();
    $("#problem_submit").click(function () {
        let xrf = new XMLHttpRequest();
        let data = new FormData();
        let id = document.getElementById("vars").dataset.clId;
        data.set(document.getElementById("vars").dataset.csrf, document.getElementById("vars").dataset.token);
        data.set("cl_id", id);
        data.set("description", $("#problem_description").val());
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
        };
    });

}