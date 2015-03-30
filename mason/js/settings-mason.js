var main = {
	init: function() {
	   $("#gallery").mason({
		  itemSelector: ".block",
		  ratio: 1,
		  sizes: [
			  [1,1]
		  ],
		  filler: {
			  itemSelector: '.filler',
			  filler_class: 'custom_filler'
		  },
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
		  columns: [
				[0, 320, 4],
				[320, 640, 4],
				[640, 960, 6],
				[960, 1080, 8],
				[1080, 1200, 8],
				[1200, 1520, 10]
			],
		  layout: 'fluid'
	  });
	}
};

$(function() {
	main.init();
});  