jQuery( document ).ready( function ( e ) {
	if ( jQuery( 'body' ).hasClass( 'has-lightbox' ) && ! jQuery( 'body' ).hasClass( 'portfolio-component' ) ) {
		jQuery( 'a[href$=".jpg"], a[href$=".jpeg"], a[href$=".gif"], a[href$=".png"]' ).each( function () {
			var imageTitle = '';
			if ( jQuery( this ).next().hasClass( 'wp-caption-text' ) ) {
				imageTitle = jQuery( this ).next().text();
			}
			
			jQuery( this ).attr( 'rel', 'lightbox' ).attr( 'title', imageTitle );
		});
		
		jQuery( 'a[rel^="lightbox"]' ).prettyPhoto({social_tools: false});
	}
});