

// summernote
$(function(){
    $('#summernote_1').summernote({
        tabsize: 2,
        height: 250,
        toolbar: [
            // [groupName, [list of button]]
            ['para', ['style']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph',]],
            ['height', ['height']],
            ['insert', ['hr']]
        ]
    });
});

var postSumNote_1 = function(){
    var content = $('textarea[name="text"]').html($('#summernote_1').code());
}

$(function(){
    $('#summernote_2').summernote({
        tabsize: 2,
        height: 250,
        toolbar: [
            // [groupName, [list of button]]
            ['para', ['style']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph',]],
            ['height', ['height']],
            ['insert', ['hr']]
        ]
    });
});

$(function(){
    $('#summernote_3').summernote({
    tabsize: 2,
    height: 250,
    toolbar: [
        // [groupName, [list of button]]
        ['para', ['style']],
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph',]],
        ['height', ['height']],
        ['insert', ['hr']]
      ]
    });
});

$(function(){
    $('#summernote_4').summernote({
    tabsize: 2,
    height: 250,
    toolbar: [
        // [groupName, [list of button]]
        ['para', ['style']],
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph',]],
        ['height', ['height']],
        ['insert', ['hr']]
      ]
    });
});

// datatable for line-up
$(document).ready(function(){
    $('#table_line-up').DataTable();
});

// datatable for program
$(document).ready(function(){
    $('#table_program').DataTable({
        order: [[4, 'asc']],
        rowGroup: {
            dataSrc: 4
        },
        fixedHeader: {
            header: true,
            footer: false
        },
        "ordering": false
    });
});

// datatable for sponsors
$(document).ready(function(){
    $('#table_sponsors').DataTable({
        "ordering": false
    });
});

// Display image without reloading the page
function displayImage(e) {
    if (e.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e){
            document.querySelector('#imageDisplay').setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(e.files[0]);
    }
}


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