$(window).load(function(){
	$('.slider').fractionSlider({
		'fullWidth'				: true,
		'controls'				: true, 
		'pager'					: false,
		'responsive'			: true,
		'dimensions'			: "1000,480",
	    'increase'				: false,
		'pauseOnHover'			: true,
		'slideTransitionSpeed' 	: 800,
		'delay'					: 0
	});

});