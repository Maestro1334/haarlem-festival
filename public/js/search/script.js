/**
 * Method triggered when an element with the class btn-view-event is clicked
 */

$('.btn-view-event').click(function () {
    let parent = $(this).parent().parent();
    let baseUrl = window.location.origin + '/haarlem_festival/';
    let category = parent.find('.category').text().toLowerCase();
    let eventName = parent.find('.name').text();

    window.location.href = baseUrl + category + '?query=' + escape(eventName);
});