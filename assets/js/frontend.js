/*global $, jQuery, alert*/

$(document).ready(function () {

	'use strict';

	// Switch Between Login & Signup

	$('.login-page h1 span').click(function () {

		$(this).addClass('selected').siblings().removeClass('selected');

		$('.login-page > i').css('color', $(this).css('color'));

		$('.login-page form').hide();

		$('.' + $(this).data('class')).fadeIn(100);

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

	// Nice Scroll
    
    // $("html").niceScroll();
    
    $('.carousel').carousel({
        
        interval: 6000
        
    });

    // Comments Carousel Edit

	$(".comments-text > div").first().addClass("active");
	$(".comments-carousel > li").first().addClass("active");
	$(".comments-carousel li:eq(0)").attr("data-slide-to","0");
	$(".comments-carousel li:eq(1)").attr("data-slide-to","1");
	$(".comments-carousel li:eq(2)").attr("data-slide-to","2");
	$(".comments-carousel li:eq(3)").attr("data-slide-to","3");

    // Caching The Scroll Top Element
    
    var scrollButton = $("#scroll-top");

    $(window).scroll(function () {
        
        if ($(this).scrollTop() >= 700) {
            
            scrollButton.fadeIn(400);
            
        } else {
            
            scrollButton.fadeOut(400);
        }
    });
    
    // Click On Button To Scroll Top
    
    scrollButton.click(function () {
        
        $("html,body").animate({ scrollTop : 0 }, 600);
        
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

	// Play Video When Click On Video Link

    /*$(".play-video").click(function(evt) {

    	evt.preventDefault();

		var $source = $('#video-preview');

		$source[0].src = $(this).attr('data-source');

		$source.parent()[0].load();

		$source.parent()[0].play();

	});*/

	function getRequest(parameter) {

       var query = window.location.search.substring(1);

       var vars = query.split("&");

       for (var i=0; i<vars.length; i++) {

            var pair = vars[i].split("=");

            if(pair[0] == parameter){return pair[1];}

       }

       return(false);

	}

	$(window).load(function() {

		var parameter = getRequest("videosource");

		if (parameter != false) {

			var $source = $('#video-preview');

			var $src = 'AdminDashboard/uploads/CoursesVideos/' + parameter;

			$source[0].src = $src;

			$source.parent()[0].load();

			$source.parent()[0].play();

		}

	});

	// Video Preview Before Upload
    
    $('.video-overlay').mouseenter(function () {
        $('.video-overlay > div:eq(1)').fadeIn(400);
    });
    
    $('.video-overlay').mouseleave(function () {
        $('.video-overlay > div:eq(1)').fadeOut(400);
    });

    // Get Link [href] Attribute From First Video In Playlist In Courses Page

    var videoHref = $('.playlist .row:eq(0) .play-video').attr('href');

    $('.video-overlay div:eq(2) a').attr('href', videoHref);

    /*
	    Display Overlay When Mouse Hover On Course Box In Profile Page
	    And Mouse Hover On Any Video Of Playlist In Courses Page
    */

    $('.course-box, .course-videos-overlay').mouseenter(function () {
        $(this).children('.course-overlay').fadeIn(200);
    });

    $('.course-box, .course-videos-overlay').mouseleave(function () {
        $(this).children('.course-overlay').fadeOut(200);
    });

    // Hidden current-play When Mouse Hover On current-video In Courses Page

    $('.current-video').mouseenter(function () {
        $('.current-play').fadeOut(200);
    });

    $('.current-video').mouseleave(function () {
        $('.current-play').fadeIn(200);
    });

    // Display Overlay When Mouse Hover On Comments Video In Profile Page

    $('.comments-overlay').mouseenter(function () {
        $(this).children('div').fadeIn(200);
    });

    $('.comments-overlay').mouseleave(function () {
        $(this).children('div').fadeOut(200);
    });

	/*$(window).load(function() {

		var playlist = $('.playlist');

		var $source = $('#video-preview');

		$source[0].src = $(playlist.find('a')[0]).attr('href');

		$('#video').load();

	});

	function init(){

		var current = 0;

		var video = $('#video');

		var playlist = $('.playlist');

		var tracks = playlist.find('.row a');

		var len = tracks.length;

		video[0].volume = .50;

		video[0].play();

		playlist.on('click','a', function(e){

			e.preventDefault();

			var link = $(this);

			current = link.parent().parent().parent().index();

			run(link, video[0]);

		});

		video[0].addEventListener('ended',function(e){

			current++;

			if((current == len) || (current > len)){

				current = 0;

				var link = playlist.find('a')[0];

			}else if(current < len){

				var link = playlist.find('a')[current];

			}

			run($(link),video[0]);

		});

	}

	function run(link, player){

			player.src = link.attr('href');

			var par = link.parent().parent().parent();

			par.addClass('current').siblings().removeClass('current');

			player.load();

			player.play();

	}

	init();*/

});