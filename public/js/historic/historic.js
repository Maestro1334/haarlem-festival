// Hides the transparent black rectangle behind the nav after scrolling down
// and makes it reappear after scrolling up
$(document).on('scroll', function(){
  if($(document).scrollTop() > 120){
    $('#black-rectangle').css({'visibility': 'hidden'});
  } else {
    $('#black-rectangle').css({'visibility': 'visible'});
  }
});

// Scrolls to the ticket section after clicking the "Take the tour" button on header
$("#schedule-scroll").click(function() {
	$('html,body').animate({
			scrollTop: $(".tickets-title").offset().top},
			'slow');
});

// When document is ready, see if hidden search input contains value
$( document ).ready(function() {
	// If the hidden input is not empty trigger the ticket button to smooth scroll down
	if($('#user-searched').val() !== ""){
		$('#schedule-scroll').trigger('click');
	}
});

// Scrolls to more information section after clicking the "more information" button in the ticket section
$("#information-scroll").click(function() {
	$('html,body').animate({
			scrollTop: $(".information-container").offset().top},
			'slow');
});

// When the buy ticket button is clicked, this function is triggered
// It looks up the values stored in the hidden inputs and displays them in the shopping cart modal
$('.button-buy').click(function () {
	let parent = $(this).parent().parent();

	// Get the input fields of the modal
	let amountInputField = $('#cart-modal #product-id');
	let eventTypeField = $('#cart-modal #ticket-type');
	let eventTitleField = $('#cart-modal #ticket-title');
	let eventDateField = $('#cart-modal #ticket-date');

	let productId;
	let title;
	let availability;

	// If button clicked is family ticket button
	// find the family ticket ID and display the family name in modal
	if ($(this).hasClass("family")) {
		productId = parseInt(parent.find('#product-family-id').val());
		let tempTitle = parent.find('#product-name').val();
		title = tempTitle.replace("Single", "Family");
		availability = parent.find('#product-availability').val() / 4;
	} else {
		productId = parseInt(parent.find('#product-id').val());
		title = parent.find('#product-name').val();
		availability = parent.find('#product-availability').val();
	}

	let type = 'HISTORIC';
	let date = parent.find('#product-date').val();

	amountInputField.val(productId);
	eventTypeField.text(type);
	eventTitleField.text(title);
	eventDateField.text(date);
	$(".product-amount-field").attr("max", availability);
});

// on click of + or - button check for availability and disable button if value is greater
$("#button-add, #button-remove").click(function () {
	let productAmountField = parseInt(($(".product-amount-field").attr("max")));

	if ($(".product-amount-field").val() > productAmountField) {
		$(".btn-add-product").prop("disabled", true);
		$(".btn-checkout").prop("disabled", true);
	 }
	else {
		$(".btn-add-product").prop("disabled", false);
		$(".btn-checkout").prop("disabled", false);
	}
});

$(".close").click(function () { // On click of popup close (x) button
	$(".btn-add-product").prop("disabled", false); // Enable buttons again
	$(".btn-checkout").prop("disabled", false);
});

// Automatically hides schedule languages other than English
$(document).ready(function() {
	$('.Dutch').hide();
	$('.Chinese').hide();
});

// Resets the language listbox to it's default language after a different language has been chosen and a different row shows
$(".language-select").on("click", function () {
	$('.language-select option').prop('selected', function() {
		return this.defaultSelected;
	});
});

// On change of language selection, shows target language row and hides this row
$('.language-select').change(function() {
	var value = ($(this).val());

	$(this).closest('.product-group').find('.'+value).show();
	$(this).parent().parent().hide();
});

// Language filter shows all rows for selected language and hides the rest
$("#language-filter").change(function() {
	var value = ($(this).val());

	if(value == 'Chinese') {
		$('.Chinese').show();
		$('.English').hide();
		$('.Dutch').hide();
	}
	else if(value == 'Dutch') {
		$('.Dutch').show();
		$('.English').hide();
		$('.Chinese').hide();
	}
	else {
		$('.English').show();
		$('.Dutch').hide();
		$('.Chinese').hide();
	}
});

