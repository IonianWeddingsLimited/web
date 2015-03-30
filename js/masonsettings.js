$(document).ready(function(){
	var firstRun = true;
	var masonMargin = 3;
	var blockHeight;
	
	/******************
	MASON 
	*******************/
	$("#gallery").mason({
		itemSelector: ".block",
		ratio: 1.5,
		sizes: [
			[1,1],
			[1,2],
			[2,1],
			[2,2],
		],
		columns: [
			[0,480,1],
			[480,780,2],
			[780,1080,3],
			
		],
		promoted: [
			[1, 1, 'tile1x1'],
			[1, 2, 'tile1x2'],
			[2, 1, 'tile2x1'],
			[2, 2, 'tile2x2'],
			[2, 3, 'tile2x3'],
			[3, 2, 'tile3x2'],
			[2, 4, 'tile2x4'],
			[4, 2, 'tile4x2']
		],
				   
		filler: {
			itemSelector: '.filler',
			filler_class: 'custom_filler'
		},
		randomSizes:false,
		randomFillers:false,
		
		layout: 'fluid',
		gutter: masonMargin
	},function(){
		if(firstRun){
			firstRun = false;
			$(window).trigger('resize');	
		}
		
		blockHeight = window['masonBlockHeight'] - 2*masonMargin;
	});

});//doc ready 