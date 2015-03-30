$(function(){
   $("#grid").mason({
      itemSelector: ".item",
      ratio: 1,
      sizes: [
          [1,1]
      ],
      filler: {
          itemSelector: '.filler',
          filler_class: 'custom_filler'
      },
      promoted: [
          [2,1, 'tile1'],
          [2,2, 'tile2'],
          [3,2, 'tile3x2'],
          [4,1, 'tile4']
      ],
      layout: 'fluid'
  });
});