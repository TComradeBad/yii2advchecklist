

function submitChange(event, item_id) {

    let formData = new FormData();
    let xrf = new XMLHttpRequest();
    let id = document.getElementById("vars").dataset.clId;
    event.preventDefault();
    formData.set(document.getElementById("vars").dataset.csrf, document.getElementById("vars").dataset.token);
    formData.set("cl_id", id);
    formData.set("item_id", item_id);
    formData.set("value", event.target.checked);
    xrf.open("Post", "/user/my-cl-upd");
    xrf.send(formData);

    ///Reload pjax on my_cl page
    document.createDocumentFragment();
    xrf.onload = function () {
        if (xrf.status == "200") {
            $.pjax.reload({container: "#grid_view", timeout: false});
        }
    };
}

function InitMyClItem() {
    //$(".active-element").off();

    $("#my_cl_grid").find(".pagination li a").off();
    $("#my_cl_grid").find(".pagination li a").click(function () {
        $.ajaxSetup({
            url: $(this).attr('href'),
            async: true,
            type: "POST",
            cache: false,
            global: false,
            success: function (html) {
                /* Replace Content */
                $('#modalContent').html(html);
            },
        });
        $.ajax();
        return false;
    });

    $("#change_my_c").off();
    $("#change_my_cl").click(function () {
        $.ajax({
            url: $(this).attr('value'),
            async: true,
            type: "POST",
            cache: false,
            global: false,
            success: function (html) {

                $('#modalContent').html(html);

            }
        });
        return false;
    });


    $("#delete_my_cl").off();
    $("#delete_my_cl").click(function () {
        $.ajax({
            url: $(this).attr('value'),
            async: true,
            type: "POST",
            cache: false,
            global: false,
            success: function (html) {

                $('#modalContent').html(html);

            }
        });
        return false;
    });

}
