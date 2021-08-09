$(document).ready(function () {

	'use strict';

	// Dashboard 

	$('.toggle-info').click(function () {

		$(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

		if ($(this).hasClass('selected')) {

			$(this).html('<i class="fa fa-plus fa-lg"></i>');

		} else {

			$(this).html('<i class="fa fa-minus fa-lg"></i>');

		}

	});

	// Trigger The Selectboxit

	$("select").selectBoxIt({

		autoWidth: false

	});

	// Hide Placeholder On Form Focus

	$('[placeholder]').focus(function () {

		$(this).attr('data-text', $(this).attr('placeholder'));

		$(this).attr('placeholder', '');

	}).blur(function () {

		$(this).attr('placeholder', $(this).attr('data-text'));

	});

	// Add Asterisk On Required Field

	$('input').each(function () {

		if ($(this).attr('required') === 'required') {

			$(this).after('<span class="asterisk">*</span>');

		}

	});

	// Convert Password Field To Text Field On Hover

	var passField = $('.password');

	$('.show-pass').hover(function () {

		passField.attr('type', 'text');

	}, function () {

		passField.attr('type', 'password');

	});

	// Confirmation Message On Button

	$('.confirm').click(function () {

		return confirm('هل أنت متأكد من تنفيذ الأمر ؟');

	});

	$('.live').keyup(function () {

		$($(this).data('class')).text($(this).val());

	});

	// Image Preview Before Upload

    function readURL(input) {

        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function (e) {

                $('#image-preview').attr('src', e.target.result);

            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image-upload").change(function() {

	  readURL(this);

	});

    // Video Preview Before Upload

    $("#video-upload").change(function(evt) {

		var $source = $('#video-preview');

		$source[0].src = URL.createObjectURL(this.files[0]);

		$source.parent()[0].load();

	});

	// Section View Option

	$('.cat h3').click(function () {

		$(this).next('.full-view').slideToggle(400);

	});

	$('.option span').click(function () {

		$(this).addClass('active').siblings('span').removeClass('active');

		if ($(this).data('view') === 'full') {

			$('.cat .full-view').slideDown(400);

		} else {

			$('.cat .full-view').slideUp(400);

		}

	});

});