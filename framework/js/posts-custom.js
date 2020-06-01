

jQuery(document).ready(function ($) {

	$('.of-color').wpColorPicker();

	$(document).on('click', '.edit-question', function (e) {
		$(this).siblings('.question').slideToggle();
	});
});