var app = {
	message: null,
	chat: null,
	geo: null,
	screen: {marker: null, this: null},
	flags: {},
	bgvideo: {
		container: null,
		screen: null,
		player: null,
		defaults: null,
		vid: null
	}
};
app.init = function(){
	// ---
	app.svg();
	app.lazyInit();
};
app.svg = function(callback=function(){}){
	$('[data-svg]').each(function(index){
		var src = $(this).attr('data-svg');
		
		if( typeof src != 'undefined') {
	
			    
				$(this).load('/images/svg/'+src+'.svg', function(){
				    
					localStorage.setItem('svg-'+src, $(this).html());
				});
		}
	});
};
app.lazyInit = function(active, callback=function(){}){
	if(active) var container = app.screen.this;
	else var container = $('body');

	container.find('.lazy-img[data-src]').each(function(){
		$(this).attr('src', $(this).attr('data-src'));
		$(this).removeAttr('data-src');
	});

	container.find('.lazy-bgr[data-src]').each(function(){
		var src = $(this).attr('data-src');
		$(this).removeAttr('data-src');

		if( typeof $(this).attr('data-size') == 'undefined' ){
			var size = 'cover';
		}
		else{ var size = $(this).attr('data-size'); }

		if( typeof $(this).attr('data-scroll') == 'undefined' ){
			var scroll = 'scroll';
		}
		else{ var scroll = $(this).attr('data-scroll'); }

		if( typeof $(this).attr('data-position') == 'undefined' ){
			var position = 'center center';
		}
		else{ var position = $(this).attr('data-position'); }

		$(this).css({
			'background': 'url(\''+src+'\') no-repeat '+position+' '+scroll,
			'-webkit-background-size': size,
			'-moz-background-size': size,
			'-o-background-size': size,
			'background-size': size
		});
	});
};
app.scripts = function(callback=function(){}){
	let scripts = [
		'https://www.googletagmanager.com/gtag/js?id=G-C9T1KWQQV7',
		'/libs/js-widgets/google-tag-manager.js',
		'/libs/js-widgets/rating-mail.js',
		'/libs/js-widgets/vk-pixel.js',
	];

	$.each(scripts,function(key,url){
		var script = document.createElement("script");
		script.src = url;

		document.head.appendChild(script);
	});
};

$(document).ready(function(){
    	setTimeout(function(){
		app.init();
		
	},400);
	
		$('body').on('click','[data-action="cities-toggle"]',function(event){
		if( $(".cities").attr('data-view') == 'active' ) { $(".cities").attr('data-view','default'); }
		else {
			$(".cities").attr('data-view','active');
		}

		event.preventDefault();
	});
	$(document).on('click', '.js-city', function() {
		$('.cities').attr('data-view','');
		const url = new URL(window.location);
		url.searchParams.set('geo', $(this).attr('data-code'));
		history.pushState(null, null, url);
		$('.js-city').removeClass("active");
		$(this).addClass("active");
		$(".header-cities span").text($(this).text());
		location.reload();  
	
	});
});