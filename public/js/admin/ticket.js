// datatable for ticket
$(document).ready(function(){
    $('#table_tickets_Dance').DataTable({
        order: [[4, 'asc']],
        rowGroup: {
            dataSrc: 6
        },
        fixedHeader: {
            header: true,
            footer: false
        },
        "ordering": false
    });

    $('#table_tickets_Historic').DataTable({
        order: [[4, 'asc']],
        rowGroup: {
            dataSrc: 6
        },
        fixedHeader: {
            header: true,
            footer: false
        },
        "ordering": false
    });

    $('#table_tickets_Food').DataTable({
        order: [[4, 'asc']],
        rowGroup: {
            dataSrc: 6
        },
        fixedHeader: {
            header: true,
            footer: false
        },
        "ordering": false
    });

    $('#table_tickets_Jazz').DataTable({
        order: [[4, 'asc']],
        rowGroup: {
            dataSrc: 6
        },
        fixedHeader: {
            header: true,
            footer: false
        },
        "ordering": false
    });

    $("#2_div").addClass("d-none");
    $("#3_div").addClass("d-none");
    $("#4_div").addClass("d-none");
});

$("#1_btn").click(function(){
    $("#1_div").removeClass("d-none");
    $("#2_div").addClass("d-none");
    $("#3_div").addClass("d-none");
    $("#4_div").addClass("d-none");
    $("#1_btn").removeClass("btn-dark");
    $("#2_btn").addClass("btn-dark");
    $("#3_btn").addClass("btn-dark");
    $("#4_btn").addClass("btn-dark");
});
$("#2_btn").click(function(){
    $("#1_div").addClass("d-none");
    $("#2_div").removeClass("d-none");
    $("#3_div").addClass("d-none");
    $("#4_div").addClass("d-none");
    $("#1_btn").addClass("btn-dark");
    $("#2_btn").removeClass("btn-dark");
    $("#3_btn").addClass("btn-dark");
    $("#4_btn").addClass("btn-dark");
});
$("#3_btn").click(function(){
    $("#1_div").addClass("d-none");
    $("#2_div").addClass("d-none");
    $("#3_div").removeClass("d-none");
    $("#4_div").addClass("d-none");
    $("#1_btn").addClass("btn-dark");
    $("#2_btn").addClass("btn-dark");
    $("#3_btn").removeClass("btn-dark");
    $("#4_btn").addClass("btn-dark");
});
$("#4_btn").click(function(){
    $("#1_div").addClass("d-none");
    $("#2_div").addClass("d-none");
    $("#3_div").addClass("d-none");
    $("#4_div").removeClass("d-none");
    $("#1_btn").addClass("btn-dark");
    $("#2_btn").addClass("btn-dark");
    $("#3_btn").addClass("btn-dark");
    $("#4_btn").removeClass("btn-dark");
});

