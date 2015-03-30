// JavaScript Document
$(function() {
	var imgbasket = [];
	// there's the gallery and the trash
		   
	$(document).ready(function() {
	//$(window).load(function() {
		
	var $trash = $( "#basket" );			
	var $gallery = $( ".imageine > .block" );
	var $imgb = imgbasket.length;

	while ($imgb>0) {
		$u = imgbasket.length - $imgb;
		elementid = imgbasket[$u];
		
		$('.gallery [data-imgid="'+ elementimg +'"]').not($item).addClass( "box-selected" ).removeClass("ui-draggable");
		$imgb--;
	}
	
	// let the gallery items be draggable
	$gallery.draggable({
		cancel: "a.ui-icon", // clicking an icon won't initiate dragging
		revert: "invalid", // when not dropped, the item will revert back to its initial position
		containment: "document",
		helper: "clone",
		cursor: "move"
	});
	
	// let the trash be droppable, accepting the gallery items
	$trash.droppable({
		accept: ".imageine > .item",
		activeClass: "ui-state-highlight",
		tolerance: 'touch',
		drop: function( event, ui ) {
			addImage( ui.draggable );
		}
		});

	// let the gallery be droppable as well, accepting items from the trash
		$gallery.droppable({
		accept: "#basket",
		activeClass: "custom-state-active",
		drop: function( event, ui ) {
			removeImage( ui.draggable );
			}
		});
	

	// image deletion function
	var unlove_icon = "<a href='link/to/recycle/script/when/we/have/js/off' title='Remove1' class='ui-icon ui-icon-refresh'>&nbsp;</a>";
	//var unlove_icon = "";
	
	function addImage( $item, $oldid ) {
		var $list = $( ".itemrow ", $trash ).length ?
		$( ".itemrow ", $trash ) :
		$( "<div class='gallery'/>" ).appendTo( $trash ),
		sOldID	=	$item.attr("ID");
		sNewID	=	sOldID.replace('pic', '' );
		
		$item.fadeIn(function() {
			$item
				.find( "a.ui-icon-trash" ).remove()
				.end()
				.find(".box-container").addClass( "box-selected" )
				.end()
				.removeClass("ui-draggable")
				.end();
		});
		var elementimg = $item.attr('data-imgid');
		
		if(jQuery.inArray(elementimg,imgbasket)==-1) {
			imgbasket.push(elementimg);
		}
		
		$('.gallery [data-imgid="'+ elementimg +'"]').not($item).addClass( "box-selected" ).removeClass("ui-draggable");
		
		if ( $( "#basket #" +  sNewID).length ) {
			
		} else {
			$clone = $item.clone(true, true);
			$clone.appendTo( $list ).fadeIn(function() {
			$clone
				.attr('id',sNewID)
				.css('top', '')
				.css('left', '')
				.css('position', '')
				.find(".box-container")
				.css({ width: "100px" })
				.css({ height: "100px" })
				.css('overflow', 'hidden')
				.append(unlove_icon)
				.find(".lazy")
				.css('position', '')
				.css({ width: "auto" })
				.css({ height: "100%" })
				.css('margin-top', '')
				.css('margin-left', '')
				.end();
			});	
	
		}
		carRefresh();
	}
	// image recycle function
	var love_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='Love2' class='ui-icon ui-icon-trash'>Love</a>";
	//var trash_icon = "";
	function removeImage( $item ) {
		
		sID		=	$item.attr("ID");
			
		$("#pic" + sID).fadeIn(function() {
			$("#pic" + sID)
				.find(".box-container")
				.append(love_icon)
				.find(".box-container").addClass( "ui-draggable" )
				.end()
				.removeClass("box-selected")
				.end();
		});
		var elementimg = $item.attr('data-imgid');
		$('.gallery [data-imgid="'+ elementimg +'"]').not($item).addClass( "ui-draggable" ).removeClass( "box-selected" );
		
		if(jQuery.inArray(elementimg,imgbasket)!=-1) {
			var $t = imgbasket.indexOf(elementimg);			
			imgbasket.splice($t, 1);
		}
		
		
		$item.fadeOut(function() {
			$item
			.remove()
			.css( "width", "100px")
			.append( trash_icon )
			.find( "img" )
			.css( "height", "100px" )
			.end()
			.css({ width: "" })
			.css({ height: "" })
			.find("a.ui-icon-trash")
			.show()
			.appendTo( $gallery )
			.fadeIn()
			.end();
		});
		
		carRefresh();
	}
	
	function carRefresh() {
		var jcarousel = $('.jcarousel');
		jcarousel.jcarousel('reload');
		return false;
	}
	// image preview function, demonstrating the ui.dialog used as a modal window
	function viewLargerImage( $link ) {
	var src = $link.attr( "href" ),
	title = $link.siblings( "img" ).attr( "alt" ),
	$modal = $( "img[src$='" + src + "']" );
	
	if ( $modal.length ) {
		$modal.dialog( "open" );
	} else {
		var img = $( "<img alt='" + title + "' width='384' height='288' style='display: none; padding: 8px;' />" )
		.attr( "src", src ).appendTo( "body" );
		setTimeout(function() {
			img.dialog({
		
				title: title,
		
				width: 400,
		
				modal: true
		
			});
			}, 1 );
		}
	}
	
	// resolve the icons behavior with event delegation
	$( ".item", "#dragable" ).click(function( event ) {
		var $item = $( this ),
		$target = $( event.target );
			
		if ( $target.is( "a.ui-icon-trash" ) ) {
			addImage( $item );		
		} else if ( $target.is( "a.ui-icon-zoomin" ) ) {
			viewLargerImage( $target );
		} else if ( $target.is( "a.ui-icon-refresh" ) ) {
			removeImage( $item );
		}
		return false;
	});
});

});