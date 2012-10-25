$(document).ready(function(){
 
	$('#login_div').click(function(){
		show_login();
	});
	
	$('#logout_div').click(function(){
		show_logout();
	});
	
	$('#upload_div').click(function(){
		show_upload();
	});

	$('.backdrop, .box').click(function(){
		close_box();
	});
	
	$('#wrapper').click(function(){
		close_login();
	});
	
});

jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft()) + "px");
    return this;
}

function close_box()
{
	$('.backdrop, .box').animate({'opacity':'0'}, 300, 'linear', function(){
		$('.backdrop, .box').css('display', 'none');
	});
}

function close_login()
{
	$('#login').animate({'right':'-360'}, 300, 'linear');
}

function imageclick(name, width, height)
{
	$('.box').html("");
	$('.backdrop, .box').css('display', 'block');
	$('.backdrop, .box').animate({'opacity':'1'}, 300, 'linear');
	$('.box').css({'width' : width, 'height' : height});
	$('.box').center();
	$('.box').html("<img src='photos/"+name+"' alt='Photo' width='"+width+"' height='"+height+"'>");
}

function show_login()
{
	$('#login').animate({'right':'0'}, 300, 'linear');
}

function show_logout()
{
	$('#logout').animate({'right':'0'}, 300, 'linear');
}

function show_upload()
{
	$('#upload').animate({'left':'0'}, 300, 'linear');
}