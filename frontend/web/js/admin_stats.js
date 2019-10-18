"use strict";

function InitClProgress(chart, data) {
    chart.data.labels = [];
    chart.data.datasets[0].data = [];
    chart.data.datasets[0].backgroundColor = [];
    chart.data.datasets[0].hoverBackgroundColor = [];
    chart.update();
    if (data.cl_done_count != "0") {
        chart.data.labels.push("Checklists done(" + data.cl_done_count + ")");
        chart.data.datasets[0].data.push(data.cl_done_count);
        chart.data.datasets[0].backgroundColor.push("#34eb3d");
        chart.data.datasets[0].hoverBackgroundColor.push("#34eb3d");
    }
    if (data.cl_in_process_count != "0") {
        chart.data.labels.push("Checklists in Progress(" + data.cl_in_process_count + ")");
        chart.data.datasets[0].data.push(data.cl_in_process_count);
        chart.data.datasets[0].backgroundColor.push("#eb4034");
        chart.data.datasets[0].hoverBackgroundColor.push("#eb4034");
    }
    if (data.cl_in_process_count == "0" && data.cl_done_count == "0") {
        chart.data.labels.push("Empty");
        chart.data.datasets[0].data.push("1");
        chart.data.datasets[0].backgroundColor.push("##9fb2d1");
        chart.data.datasets[0].hoverBackgroundColor.push("#9fb2d1");
    }
}

function InitSD(chart,data) {
    chart.data.labels = [];
    chart.data.datasets[0].data = [];
    chart.data.datasets[0].backgroundColor = [];
    chart.data.datasets[0].hoverBackgroundColor = [];
    chart.update();
    if (data.cl_sd != "0") {
        chart.data.labels.push("Have  problems(" + data.cl_sd + ")");
        chart.data.datasets[0].data.push(data.cl_sd);
        chart.data.datasets[0].backgroundColor.push("#eb1f26");
        chart.data.datasets[0].hoverBackgroundColor.push("#eb2929");
    }

    if (data.cl_on_review != "0") {
        chart.data.labels.push("On review(" + data.cl_on_review + ")");
        chart.data.datasets[0].data.push(data.cl_on_review);
        chart.data.datasets[0].backgroundColor.push("#fff700");
        chart.data.datasets[0].hoverBackgroundColor.push("#fff700");
    }
    if (data.cl_good != "0") {
        chart.data.labels.push("Good one" + data.cl_good + ")");
        chart.data.datasets[0].data.push(data.cl_good);
        chart.data.datasets[0].backgroundColor.push("#43f05d");
        chart.data.datasets[0].hoverBackgroundColor.push("#43f05d");
    }
    if (data.cl_in_process_count == "0" && data.cl_done_count == "0") {
        chart.data.labels.push("Empty");
        chart.data.datasets[0].data.push("1");
        chart.data.datasets[0].backgroundColor.push("##9fb2d1");
        chart.data.datasets[0].hoverBackgroundColor.push("#9fb2d1");
    }
}

$(document).ready(function () {
    let progress_chart = new Chart($("#ProgressChart"), {
        type: "pie",
        data: {
            labels: [],
            datasets: [{
                backgroundColor: [],
                hoverBackgroundColor: [],
                data: []
            }]
        },
        options: {
            animation: {
                duration: 1200
            },
        }
    });
    let sd_chart = new Chart($("#SdChart"), {
        type: "pie",
        data: {
            labels: [],
            datasets: [{
                backgroundColor: [],
                hoverBackgroundColor: [],
                data: [0, 0]
            }]
        },
        options: {
            animation: {
                duration: 1200
            },
        }
    });


    $(".view_stats").click(function () {

        $.ajax({
            url: $(this).attr("value"),
            type: "POST",
            success: function (data) {
                $("#info_user_name").html(data.username);
                $("#info_cl_done_time").html(data.last_cl_done);
                $("#info_task_done_time").html(data.last_task_done);
                InitClProgress(progress_chart, data);
                InitSD(sd_chart,data);
                progress_chart.update();
                sd_chart.update();
                $("#modal").modal("show");
            }
        });


    });

    $("#modal").on("hidden.bs.modal", function () {
        progress_chart.reset();
        sd_chart.reset();
    });
});

