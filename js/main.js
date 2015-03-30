var windowsize = $(window).width();

$(window).resize(function() {
	var windowsize = $(window).width();
});

if (windowsize <= 480) {
	iTile1x1Width	=	12;
	iTile1x1Height	=	12;
	
	iTile1x2Width	=	12;
	iTile1x2Height	=	24;

	iTile2x1Width	=	12;
	iTile2x1Height	=	6;
	
	iTile2x2Width	=	12;
	iTile2x2Height	=	12;

	iTile2x3Width	=	12;
	iTile2x3Height	=	18;
	
	iTile3x2Width	=	12;
	iTile3x2Height	=	8;
	
	iTile2x4Width	=	12;
	iTile2x4Height	=	6;
	
	iTile4x2Width	=	12;
	iTile4x2Height	=	6;
} else {
	var iTile1x1Width	=	1;
	var iTile1x1Height	=	1;
	
	var iTile1x2Width	=	1;
	var iTile1x2Height	=	2;

	var iTile2x1Width	=	2;
	var iTile2x1Height	=	1;
	
	var iTile2x2Width	=	2;
	var iTile2x2Height	=	2;

	var iTile2x3Width	=	2;
	var iTile2x3Height	=	3;
	
	var iTile3x2Width	=	3;
	var iTile3x2Height	=	2;
	
	var iTile2x4Width	=	2;
	var iTile2x4Height	=	4;
	
	var iTile4x2Width	=	4;
	var iTile4x2Height	=	2;	
}

var main0 = {
	init: function() {
		$("#gallery0").mason({
			itemSelector: ".block",
			ratio: 1,
			sizes: [	
				[1,1]
			],
			columns: [
				[0, 480, 12],
				[480, 640, 4],
				[640, 960, 6],
				[960, 1080, 8],
				[1080, 1200, 8],
				[1200, 1520, 10]
			],
			promoted: [
				[iTile1x1Width, iTile1x1Height, 'tile1x1'],
				[iTile1x2Width, iTile1x2Height, 'tile1x2'],
				[iTile2x1Width, iTile2x1Height, 'tile2x1'],
				[iTile2x2Width, iTile2x2Height, 'tile2x2'],
				[iTile2x3Width, iTile2x3Height, 'tile2x3'],	
				[iTile3x2Width, iTile3x2Height, 'tile3x2'],
				[iTile2x4Width, iTile2x4Height, 'tile2x4'],
				[iTile4x2Width, iTile4x2Height, 'tile4x2']
			],	   
			filler: {
				itemSelector: '.filler',
				filler_class: 'custom_filler'
			},
			randomSizes:false,
			randomFillers:false,
			layout: 'fluid',
			gutter: 0
		}
//			function(){
//				blockHeight = window['masonBlockHeight'] - 2*masonMargin;
//			}
		);
	}
}
var main1 = {
	init: function() {
		$("#gallery1").mason({
			itemSelector: ".block",
			ratio: 1,
			sizes: [	
				[1,1]
			],
			columns: [
				[0, 480, 12],
				[480, 640, 4],
				[640, 960, 6],
				[960, 1080, 8],
				[1080, 1200, 8],
				[1200, 1520, 10]
			],
			promoted: [
				[iTile1x1Width, iTile1x1Height, 'tile1x1'],
				[iTile1x2Width, iTile1x2Height, 'tile1x2'],
				[iTile2x1Width, iTile2x1Height, 'tile2x1'],
				[iTile2x2Width, iTile2x2Height, 'tile2x2'],
				[iTile2x3Width, iTile2x3Height, 'tile2x3'],	
				[iTile3x2Width, iTile3x2Height, 'tile3x2'],
				[iTile2x4Width, iTile2x4Height, 'tile2x4'],
				[iTile4x2Width, iTile4x2Height, 'tile4x2']
			],	   
			filler: {
				itemSelector: '.filler',
				filler_class: 'custom_filler'
			},
			randomSizes:false,
			randomFillers:false,
			layout: 'fluid',
			gutter: 0
		}
//			function(){
//				blockHeight = window['masonBlockHeight'] - 2*masonMargin;
//			}
		);
	}
}
var main2 = {
	init: function() {
		$("#gallery2").mason({
			itemSelector: ".block",
			ratio: 1,
			sizes: [	
				[1,1]
			],
			columns: [
				[0, 480, 12],
				[480, 640, 4],
				[640, 960, 6],
				[960, 1080, 8],
				[1080, 1200, 8],
				[1200, 1520, 10]
			],
			promoted: [
				[iTile1x1Width, iTile1x1Height, 'tile1x1'],
				[iTile1x2Width, iTile1x2Height, 'tile1x2'],
				[iTile2x1Width, iTile2x1Height, 'tile2x1'],
				[iTile2x2Width, iTile2x2Height, 'tile2x2'],
				[iTile2x3Width, iTile2x3Height, 'tile2x3'],	
				[iTile3x2Width, iTile3x2Height, 'tile3x2'],
				[iTile2x4Width, iTile2x4Height, 'tile2x4'],
				[iTile4x2Width, iTile4x2Height, 'tile4x2']
			],	   
			filler: {
				itemSelector: '.filler',
				filler_class: 'custom_filler'
			},
			randomSizes:false,
			randomFillers:false,
			layout: 'fluid',
			gutter: 0
		}
//			function(){
//				blockHeight = window['masonBlockHeight'] - 2*masonMargin;
//			}
		);
	}
}
var main3 = {
	init: function() {
		$("#gallery3").mason({
			itemSelector: ".block",
			ratio: 1,
			sizes: [	
				[1,1]
			],
			columns: [
				[0, 480, 12],
				[480, 640, 4],
				[640, 960, 6],
				[960, 1080, 8],
				[1080, 1200, 8],
				[1200, 1520, 10]
			],
			promoted: [
				[iTile1x1Width, iTile1x1Height, 'tile1x1'],
				[iTile1x2Width, iTile1x2Height, 'tile1x2'],
				[iTile2x1Width, iTile2x1Height, 'tile2x1'],
				[iTile2x2Width, iTile2x2Height, 'tile2x2'],
				[iTile2x3Width, iTile2x3Height, 'tile2x3'],	
				[iTile3x2Width, iTile3x2Height, 'tile3x2'],
				[iTile2x4Width, iTile2x4Height, 'tile2x4'],
				[iTile4x2Width, iTile4x2Height, 'tile4x2']
			],	   
			filler: {
				itemSelector: '.filler',
				filler_class: 'custom_filler'
			},
			randomSizes:false,
			randomFillers:false,
			layout: 'fluid',
			gutter: 0
		}
//			function(){
//				blockHeight = window['masonBlockHeight'] - 2*masonMargin;
//			}
		);
	}
}
var main4 = {
	init: function() {
		$("#gallery4").mason({
			itemSelector: ".block",
			ratio: 1,
			sizes: [	
				[1,1]
			],
			columns: [
				[0, 480, 12],
				[480, 640, 4],
				[640, 960, 6],
				[960, 1080, 8],
				[1080, 1200, 8],
				[1200, 1520, 10]
			],
			promoted: [
				[iTile1x1Width, iTile1x1Height, 'tile1x1'],
				[iTile1x2Width, iTile1x2Height, 'tile1x2'],
				[iTile2x1Width, iTile2x1Height, 'tile2x1'],
				[iTile2x2Width, iTile2x2Height, 'tile2x2'],
				[iTile2x3Width, iTile2x3Height, 'tile2x3'],	
				[iTile3x2Width, iTile3x2Height, 'tile3x2'],
				[iTile2x4Width, iTile2x4Height, 'tile2x4'],
				[iTile4x2Width, iTile4x2Height, 'tile4x2']
			],	   
			filler: {
				itemSelector: '.filler',
				filler_class: 'custom_filler'
			},
			randomSizes:false,
			randomFillers:false,
			layout: 'fluid',
			gutter: 0
		}
//			function(){
//				blockHeight = window['masonBlockHeight'] - 2*masonMargin;
//			}
		);
	}
}
var main5 = {
	init: function() {
		$("#gallery5").mason({
			itemSelector: ".block",
			ratio: 1,
			sizes: [	
				[1,1]
			],
			columns: [
				[0, 480, 12],
				[480, 640, 4],
				[640, 960, 6],
				[960, 1080, 8],
				[1080, 1200, 8],
				[1200, 1520, 10]
			],
			promoted: [
				[iTile1x1Width, iTile1x1Height, 'tile1x1'],
				[iTile1x2Width, iTile1x2Height, 'tile1x2'],
				[iTile2x1Width, iTile2x1Height, 'tile2x1'],
				[iTile2x2Width, iTile2x2Height, 'tile2x2'],
				[iTile2x3Width, iTile2x3Height, 'tile2x3'],	
				[iTile3x2Width, iTile3x2Height, 'tile3x2'],
				[iTile2x4Width, iTile2x4Height, 'tile2x4'],
				[iTile4x2Width, iTile4x2Height, 'tile4x2']
			],	   
			filler: {
				itemSelector: '.filler',
				filler_class: 'custom_filler'
			},
			randomSizes:false,
			randomFillers:false,
			layout: 'fluid',
			gutter: 0
		}
//			function(){
//				blockHeight = window['masonBlockHeight'] - 2*masonMargin;
//			}
		);
	}
}
var main6 = {
	init: function() {
		$("#gallery6").mason({
			itemSelector: ".block",
			ratio: 1,
			sizes: [	
				[1,1]
			],
			columns: [
				[0, 480, 12],
				[480, 640, 4],
				[640, 960, 6],
				[960, 1080, 8],
				[1080, 1200, 8],
				[1200, 1520, 10]
			],
			promoted: [
				[iTile1x1Width, iTile1x1Height, 'tile1x1'],
				[iTile1x2Width, iTile1x2Height, 'tile1x2'],
				[iTile2x1Width, iTile2x1Height, 'tile2x1'],
				[iTile2x2Width, iTile2x2Height, 'tile2x2'],
				[iTile2x3Width, iTile2x3Height, 'tile2x3'],	
				[iTile3x2Width, iTile3x2Height, 'tile3x2'],
				[iTile2x4Width, iTile2x4Height, 'tile2x4'],
				[iTile4x2Width, iTile4x2Height, 'tile4x2']
			],	   
			filler: {
				itemSelector: '.filler',
				filler_class: 'custom_filler'
			},
			randomSizes:false,
			randomFillers:false,
			layout: 'fluid',
			gutter: 0
		}
//			function(){
//				blockHeight = window['masonBlockHeight'] - 2*masonMargin;
//			}
		);
	}
}
var main7 = {
	init: function() {
		$("#gallery7").mason({
			itemSelector: ".block",
			ratio: 1,
			sizes: [	
				[1,1]
			],
			columns: [
				[0, 480, 12],
				[480, 640, 4],
				[640, 960, 6],
				[960, 1080, 8],
				[1080, 1200, 8],
				[1200, 1520, 10]
			],
			promoted: [
				[iTile1x1Width, iTile1x1Height, 'tile1x1'],
				[iTile1x2Width, iTile1x2Height, 'tile1x2'],
				[iTile2x1Width, iTile2x1Height, 'tile2x1'],
				[iTile2x2Width, iTile2x2Height, 'tile2x2'],
				[iTile2x3Width, iTile2x3Height, 'tile2x3'],	
				[iTile3x2Width, iTile3x2Height, 'tile3x2'],
				[iTile2x4Width, iTile2x4Height, 'tile2x4'],
				[iTile4x2Width, iTile4x2Height, 'tile4x2']
			],	   
			filler: {
				itemSelector: '.filler',
				filler_class: 'custom_filler'
			},
			randomSizes:false,
			randomFillers:false,
			layout: 'fluid',
			gutter: 0
		}
//			function(){
//				blockHeight = window['masonBlockHeight'] - 2*masonMargin;
//			}
		);
	}
}
var main8 = {
	init: function() {
		$("#gallery8").mason({
			itemSelector: ".block",
			ratio: 1,
			sizes: [	
				[1,1]
			],
			columns: [
				[0, 480, 12],
				[480, 640, 4],
				[640, 960, 6],
				[960, 1080, 8],
				[1080, 1200, 8],
				[1200, 1520, 10]
			],
			promoted: [
				[iTile1x1Width, iTile1x1Height, 'tile1x1'],
				[iTile1x2Width, iTile1x2Height, 'tile1x2'],
				[iTile2x1Width, iTile2x1Height, 'tile2x1'],
				[iTile2x2Width, iTile2x2Height, 'tile2x2'],
				[iTile2x3Width, iTile2x3Height, 'tile2x3'],	
				[iTile3x2Width, iTile3x2Height, 'tile3x2'],
				[iTile2x4Width, iTile2x4Height, 'tile2x4'],
				[iTile4x2Width, iTile4x2Height, 'tile4x2']
			],	   
			filler: {
				itemSelector: '.filler',
				filler_class: 'custom_filler'
			},
			randomSizes:false,
			randomFillers:false,
			layout: 'fluid',
			gutter: 0
		}
//			function(){
//				blockHeight = window['masonBlockHeight'] - 2*masonMargin;
//			}
		);
	}
}
var main9 = {
	init: function() {
		$("#gallery9").mason({
			itemSelector: ".block",
			ratio: 1,
			sizes: [	
				[1,1]
			],
			columns: [
				[0, 480, 12],
				[480, 640, 4],
				[640, 960, 6],
				[960, 1080, 8],
				[1080, 1200, 8],
				[1200, 1520, 10]
			],
			promoted: [
				[iTile1x1Width, iTile1x1Height, 'tile1x1'],
				[iTile1x2Width, iTile1x2Height, 'tile1x2'],
				[iTile2x1Width, iTile2x1Height, 'tile2x1'],
				[iTile2x2Width, iTile2x2Height, 'tile2x2'],
				[iTile2x3Width, iTile2x3Height, 'tile2x3'],	
				[iTile3x2Width, iTile3x2Height, 'tile3x2'],
				[iTile2x4Width, iTile2x4Height, 'tile2x4'],
				[iTile4x2Width, iTile4x2Height, 'tile4x2']
			],	   
			filler: {
				itemSelector: '.filler',
				filler_class: 'custom_filler'
			},
			randomSizes:false,
			randomFillers:false,
			layout: 'fluid',
			gutter: 0
		}
//			function(){
//				blockHeight = window['masonBlockHeight'] - 2*masonMargin;
//			}
		);
	}
}

$(document).load($(function() {
	main0.init();
	main1.init();
	main2.init();
	main3.init();
	main4.init();
	main5.init();
	main6.init();
	main7.init();
	main8.init();
	main9.init();
}));//doc ready