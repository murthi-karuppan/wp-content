jQuery(document).ready(function($) {
	$('#tax_exempt_id_field').hide();

	$('#tax_exempt_checkbox').on('click', function (event) {
		$('#tax_exempt_id_field').slideDown('slow');
		$( 'body' ).trigger( 'update_checkout' );
	});

	$('#tax_exempt_id').on('change', function (event) {
		$( 'body' ).trigger( 'update_checkout' );
	});
});
