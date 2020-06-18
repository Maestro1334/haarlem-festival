$( document ).ready(function() {
    // Trigger the search field to check if there is some text in it
    $('#ticket-search').trigger('keyup');

    // If the search field is not empty trigger the ticket button to smooth scroll down
    if($('#ticket-search').val() !== ""){
        $('#btn-tickets').trigger('click');
    }
});


// Scrolls to the ticket section after clicking the "Take the tour" button on header
$("#btn-tickets").click(function() {
    $('html,body').animate({
            scrollTop: $("#tickets").offset().top - 80},
        'slow');
});


/** Method sending data to shopping cart modal **/
$('.btn-buy-ticket').click(function () {
    let parent = $(this).parent().parent();

    // Get the input fields of the modal
    let amountInputField = $('#cart-modal #product-id');
    let eventTypeField = $('#cart-modal #ticket-type');
    let eventTitleField = $('#cart-modal #ticket-title');
    let eventDateField = $('#cart-modal #ticket-date');

    let productId = parseInt(parent.find('#product-id').val());
    let type = 'DANCE';
    let title = parent.find('.ticket-title').text();
    let date = parent.find('#product-date').val();

    amountInputField.val(productId);
    eventTypeField.text(type);
    eventTitleField.text(title);
    eventDateField.text(date);
});


/** Filter for the search method **/

$('#ticket-search').keyup(function() {
    let input = $(this).val().toUpperCase();
    let ticketRows = $('.ticket-container .ticket-row');

    ticketRows.each(function(index) {
        let ticketTitle = $(this).find('.ticket-title').text();

        // Show or hide the table row if it matches the filter query
        if (ticketTitle.toUpperCase().indexOf(input) >= 0) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });

    let ticketContainers = $('.ticket-container');

    ticketContainers.each(function() {
        let parent = $(this).parent();
        var visibleCount = $(this).find('.ticket-row').filter(function(){
            if($(this).css('display') !== 'none')
                return $(this);
        }).length;

        if(visibleCount <= 0) {
            parent.find('.ticket-extras').hide();
            $(this).find('.ticket-no-result').css('display', '');
        } else {
            parent.find('.ticket-extras').show();
            $(this).find('.ticket-no-result').css('display', 'none');
        }
    });
});