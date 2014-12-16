var $j = jQuery.noConflict();
$j(function() {

	// This controls the Skip Overview link on the Overview screen
	$j('#skip_overview').click(function(){
		$j('#skip_overview_form').submit();
	});

	// Date Picker
	if( $j.isFunction($j.fn.datepicker) ) {
		$j('.datepicker').datepicker({
			dateFormat: 'dd/mm/yy'
		});
	}

	// Chosen
	if( $j.isFunction($j.fn.chosen) ) {
		$j(".chzn-select").chosen({
			search_contains: true
		});
	}

	// Sortable export columns
	if( $j.isFunction($j.fn.sortable) ) {
		$j('table.ui-sortable').sortable({
			items:'tr',
			cursor:'move',
			axis:'y',
			handle: 'td',
			scrollSensitivity:40,
			helper:function(e,ui){
				ui.children().each(function(){
					jQuery(this).width(jQuery(this).width());
				});
				ui.css('left', '0');
				return ui;
			},
			start:function(event,ui){
				ui.item.css('background-color','#f6f6f6');
			},
			stop:function(event,ui){
				ui.item.removeAttr('style');
				field_row_indexes(this);
			}
		});
	
		function field_row_indexes(obj) {
			rows = $j(obj).find('tr');
			$j(rows).each(function(index, el){
				$j('input.field_order', el).val( parseInt( $j(el).index() ) );
			});
		};
	}

	// Select all field options for this export type
	$j('.checkall').click(function () {
		$j(this).closest('.postbox').find(':checkbox').attr('checked', true);
	});
	// Unselect all field options for this export type
	$j('.uncheckall').click(function () {
		$j(this).closest('.postbox').find(':checkbox').attr('checked', false);
	});

	$j('.export-types').hide();
	$j('.export-options').hide();

	// Categories
	$j('#export-products-filters-categories').hide();
	if( $j('#products-filters-categories').attr('checked') ) {
		$j('#export-products-filters-categories').show();
	}
	// Tags
	$j('#export-products-filters-tags').hide();
	if( $j('#products-filters-tags').attr('checked') ) {
		$j('#export-products-filters-tags').show();
	}
	// Brands
	$j('#export-products-filters-brands').hide();
	if( $j('#products-filters-brands').attr('checked') ) {
		$j('#export-products-filters-brands').show();
	}
	// Product Status
	$j('#export-products-filters-status').hide();
	if( $j('#products-filters-status').attr('checked') ) {
		$j('#export-products-filters-status').show();
	}
	$j('#export-products-filters-type').hide();
	if( $j('#products-filters-type').attr('checked') ) {
		$j('#export-products-filters-type').show();
	}
	$j('#export-products-filters-stock').hide();
	if( $j('#products-filters-stock').attr('checked') ) {
		$j('#export-products-filters-stock').show();
	}

	$j('#export-category').hide();

	$j('#export-tag').hide();

	$j('#export-brand').hide();

	$j('#export-order').hide();
	$j('#export-orders-filters-status').hide();
	if( $j('#orders-filters-status').attr('checked') ) {
		$j('#export-orders-filters-status').show();
	}
	$j('#export-orders-filters-date').hide();
	if( $j('#orders-filters-date').attr('checked') ) {
		$j('#export-orders-filters-date').show();
	}
	$j('#export-orders-filters-customer').hide();
	if( $j('#orders-filters-customer').attr('checked') ) {
		$j('#export-orders-filters-customer').show();
	}
	$j('#export-orders-filters-user_role').hide();
	if( $j('#orders-filters-user_role').attr('checked') ) {
		$j('#export-orders-filters-user_role').show();
	}
	$j('#export-orders-filters-coupon').hide();
	if( $j('#orders-filters-coupon').attr('checked') ) {
		$j('#export-orders-filters-coupon').show();
	}
	$j('#export-orders-filters-category').hide();
	if( $j('#orders-filters-category').attr('checked') ) {
		$j('#export-orders-filters-category').show();
	}
	$j('#export-orders-filters-tag').hide();
	if( $j('#orders-filters-tag').attr('checked') ) {
		$j('#export-orders-filters-tag').show();
	}

	$j('#export-customers-filters-status').hide();
	if( $j('#customers-filters-status').attr('checked') ) {
		$j('#export-customers-filters-status').show();
	}
	$j('#export-customer').hide();
	$j('#export-user').hide();
	$j('#export-coupon').hide();
	$j('#export-subscription').hide();
	$j('#export-product_vendor').hide();
	$j('#export-shipping_class').hide();
	$j('#export-attribute').hide();

	$j('#products-filters-categories').click(function(){
		$j('#export-products-filters-categories').toggle();
	});
	$j('#products-filters-tags').click(function(){
		$j('#export-products-filters-tags').toggle();
	});
	$j('#products-filters-status').click(function(){
		$j('#export-products-filters-status').toggle();
	});
	$j('#products-filters-type').click(function(){
		$j('#export-products-filters-type').toggle();
	});
	$j('#products-filters-stock').click(function(){
		$j('#export-products-filters-stock').toggle();
	});

	$j('#orders-filters-date').click(function(){
		$j('#export-orders-filters-date').toggle();
	});
	$j('#orders-filters-status').click(function(){
		$j('#export-orders-filters-status').toggle();
	});
	$j('#orders-filters-customer').click(function(){
		$j('#export-orders-filters-customer').toggle();
	});
	$j('#orders-filters-user_role').click(function(){
		$j('#export-orders-filters-user_role').toggle();
	});
	$j('#orders-filters-coupon').click(function(){
		$j('#export-orders-filters-coupon').toggle();
	});
	$j('#orders-filters-category').click(function(){
		$j('#export-orders-filters-category').toggle();
	});
	$j('#orders-filters-tag').click(function(){
		$j('#export-orders-filters-tag').toggle();
	});

	$j('#customers-filters-status').click(function(){
		$j('#export-customers-filters-status').toggle();
	});

	// Export types
	$j('#product').click(function(){
		$j('.export-types').hide();
		$j('#export-product').show();

		$j('.export-options').hide();
		$j('.product-options').show();
	});
	$j('#category').click(function(){
		$j('.export-types').hide();
		$j('#export-category').show();

		$j('.export-options').hide();
		$j('.category-options').show();
	});
	$j('#tag').click(function(){
		$j('.export-types').hide();
		$j('#export-tag').show();

		$j('.export-options').hide();
		$j('.tag-options').show();
	});
	$j('#brand').click(function(){
		$j('.export-types').hide();
		$j('#export-brand').show();

		$j('.export-options').hide();
		$j('.brand-options').show();
	});
	$j('#order').click(function(){
		$j('.export-types').hide();
		$j('#export-order').show();

		$j('.export-options').hide();
		$j('.order-options').show();
	});
	$j('#customer').click(function(){
		$j('.export-types').hide();
		$j('#export-customer').show();

		$j('.export-options').hide();
		$j('.customer-options').show();
	});
	$j('#user').click(function(){
		$j('.export-types').hide();
		$j('#export-user').show();

		$j('.export-options').hide();
		$j('.user-options').show();
	});
	$j('#coupon').click(function(){
		$j('.export-types').hide();
		$j('#export-coupon').show();

		$j('.export-options').hide();
		$j('.coupon-options').show();
	});
	$j('#subscription').click(function(){
		$j('.export-types').hide();
		$j('#export-subscription').show();

		$j('.export-options').hide();
		$j('.subscription-options').show();
	});
	$j('#product_vendor').click(function(){
		$j('.export-types').hide();
		$j('#export-product_vendor').show();

		$j('.export-options').hide();
		$j('.product_vendor-options').show();
	});
	$j('#shipping_class').click(function(){
		$j('.export-types').hide();
		$j('#export-shipping_class').show();

		$j('.export-options').hide();
		$j('.shipping_class-options').show();
	});
	$j('#attribute').click(function(){
		$j('.export-types').hide();
		$j('#export-attribute').show();

		$j('.export-options').hide();
		$j('.attribute-options').show();
	});

	// Export button
	$j('#export_product').click(function(){
		$j('input:radio[name=dataset]:nth(0)').attr('checked',true);
	});
	$j('#export_category').click(function(){
		$j('input:radio[name=dataset]:nth(1)').attr('checked',true);
	});
	$j('#export_tag').click(function(){
		$j('input:radio[name=dataset]:nth(2)').attr('checked',true);
	});
	$j('#export_brand').click(function(){
		$j('input:radio[name=dataset]:nth(3)').attr('checked',true);
	});
	$j('#export_order').click(function(){
		$j('input:radio[name=dataset]:nth(4)').attr('checked',true);
	});
	$j('#export_customer').click(function(){
		$j('input:radio[name=dataset]:nth(5)').attr('checked',true);
	});
	$j('#export_user').click(function(){
		$j('input:radio[name=dataset]:nth(6)').attr('checked',true);
	});
	$j('#export_coupon').click(function(){
		$j('input:radio[name=dataset]:nth(7)').attr('checked',true);
	});
	$j('#export_subscription').click(function(){
		$j('input:radio[name=dataset]:nth(8)').attr('checked',true);
	});
	$j('#export_product_vendor').click(function(){
		$j('input:radio[name=dataset]:nth(9)').attr('checked',true);
	});
	$j('#export_shipping_class').click(function(){
		$j('input:radio[name=dataset]:nth(10)').attr('checked',true);
	});
	$j('#export_attribute').click(function(){
		$j('input:radio[name=dataset]:nth(11)').attr('checked',true);
	});

	$j("#auto_type").change(function () {
		var type = $j('select[name=auto_type]').val();
		$j('.auto_type_options .export-options').hide();
		$j('.auto_type_options .'+type+'-options').show();
	});

	$j("#auto_method").change(function () {
		var type = $j('select[name=auto_method]').val();
		$j('.auto_method_options .export-options').hide();
		$j('.auto_method_options .'+type+'-options').show();
	});

	$j(document).ready(function() {
		// This auto-selects the export type based on the link from the Overview screen
		var href = jQuery(location).attr('href');
		// If this is the Export tab
		if (href.toLowerCase().indexOf('tab=export') >= 0) {
			// If the URL includes an in-line link
			if (href.toLowerCase().indexOf('#') >= 0 ) {
				var type = href.substr(href.indexOf("#") + 1);
				var type = type.replace('export-','');
				$j('#'+type).trigger('click');
			} else {
				// This auto-selects the last known export type based on stored WordPress option, defaults to Products
				var type = $j('input:radio[name=dataset]:checked').val();
				$j('#'+type).trigger('click');
			}
		} else if (href.toLowerCase().indexOf('tab=settings') >= 0) {
			$j("#auto_type").trigger("change");
			$j("#auto_method").trigger("change");
		} else {
			// This auto-selects the last known export type based on stored WordPress option, defaults to Products
			var type = $j('input:radio[name=dataset]:checked').val();
			$j('#'+type).trigger('click');
		}
	});

});