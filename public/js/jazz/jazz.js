// Scrolls to the ticket section after clicking the "TICKETS" button
$("#jazz-button-tickets").click(function() {
  $('html,body').animate({
        scrollTop: $("#tickets-link").offset().top - 75},
      'slow');
});

// Scrolls to the ticket section after clicking the "PROGRAM" button
$("#jazz-button-program").click(function() {
  $('html,body').animate({
        scrollTop: $("#jazz-program-container").offset().top - 80},
      'slow');
});

// Scrolls to the ticket section after clicking the "LINEUP" button
$("#jazz-button-lineup").click(function() {
  $('html,body').animate({
        scrollTop: $("#lineup-link").offset().top - 40},
      'slow');
});

$( document ).ready(function() {
  // Trigger the search field to check if there is some text in it
  $('#searchTicket').trigger('keyup');

  // If the search field is not empty trigger the ticket button to smooth scroll down
  if($('#searchTicket').val() !== ""){
    $('#jazz-button-tickets').trigger('click');
  }
});

// Loops trough all ticket table rows and hides those that don't match the search query
function filterArtists() {
  // Get input text from user
  let input = document.getElementById("searchTicket");

  // Transform the text to uppercase
  let filter = input.value.toUpperCase();

  // Get table rows from thursday
  let table = document.getElementsByClassName("jazz-ticket-table");

  for (let i=0; i < table.length; i++){
    // Get the table rows
    let tr = table[i].getElementsByTagName("tr");

    // Filter table rows based on the filter query
    filterTableRows(filter, tr);
  }
}

function filterTableRows(filter, tr){
  // Loop through all the table rows and hide those that don't match the filter text
  for (let i = 0; i < tr.length; i++) {
    // Get correct table data to filter by
    let td = tr[i].getElementsByTagName("td")[2];

    // If value td is not filled skip this row
    if (!td) {
      continue;
    }

    // Get the value/text within the table data
    let txtValue = td.textContent || td.innerText;

    // Show or hide the table row if it matches the filter query
    if (txtValue.toUpperCase().indexOf(filter) >= 0) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  }
}

$('.jazz-button-buy').click(function () {
  let parent = $(this).parent().parent();

  // Get the input fields of the modal
  let amountInputField = $('#cart-modal #product-id');
  let eventTypeField = $('#cart-modal #ticket-type');
  let eventTitleField = $('#cart-modal #ticket-title');
  let eventDateField = $('#cart-modal #ticket-date');

  let productId = parseInt(parent.find('#product-id').val());
  let type = 'JAZZ';
  let title = parent.find('.jazz-ticket-artist').text();
  let date = parent.find('#product-date').val();

  amountInputField.val(productId);
  eventTypeField.text(type);
  eventTitleField.text(title);
  eventDateField.text(date);
});
