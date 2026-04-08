<?php
/**
 * WooCommerce functions
 */
if ( function_exists( 'is_woocommerce' ) ) :


	// Change Add to Cart button text on archive pages
	function custom_add_to_cart_text( $text ) {
		return __( 'Buy', 'woocommerce' ); // Replace with your preferred text
	}
	add_filter( 'woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text' );

	// Hide H1 if Hero Shop archive page
	add_filter('woocommerce_show_page_title', '__return_false');

	/**
	* Remove the breadcrumbs
	*/
	function woo_remove_wc_breadcrumbs() {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	}
	add_action( 'init', 'woo_remove_wc_breadcrumbs' );

	/**
	* Single Product page related products by categories and tags
	*/
	apply_filters( 'woocommerce_get_related_product_cat_terms', wc_get_product_term_ids( $product_id, 'product_cat' ), $product_id );
	apply_filters( 'woocommerce_get_related_product_tag_terms', wc_get_product_term_ids( $product_id, 'product_tag' ), $product_id );


	/**
	* Add description to Woo related items
	*/
	function woo_show_excerpt_shop_page() {
		global $product;
		echo '<p class="prod-desc">'.wp_trim_words( $product->post->post_excerpt, 20 ).'</p>'; 
	}
	add_action( 'woocommerce_after_shop_loop_item_title', 'woo_show_excerpt_shop_page', 5 );

	/**
	* Change Woo Product H1 Tag to H1 if hero H1
	*/
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	function sgl_template_single_title() {
		the_title( '<h1 class="h2 product_title entry-title">', '</h1>' );
	}
	add_action( 'woocommerce_single_product_summary', 'sgl_template_single_title', 5 );

	/*
	* WooCommerce Total Product Price for single prod template
	*/
	/*
	* WooCommerce Total Product Price for single prod template
	*/
	function woocommerce_total_product_price() {
		global $woocommerce, $product;
		$currency = get_woocommerce_currency_symbol().number_format(wc_get_price_excluding_tax($product), 2).' each <i>&nbsp;</i> total: </span> <span class="product-total-price_sum price" att_price="'.number_format(wc_get_price_excluding_tax($product), 2).'">'.get_woocommerce_currency_symbol().number_format(wc_get_price_excluding_tax($product), 2);
		echo <<<HTML
			<style>.woocommerce.single-product .summary .woocommerce-variation-price{display:none}</style>
			<div id="product_total_price" class="product-total-price" style="display:none"><span class="product-total-price_caption" style="opacity:0.7">{$currency}</span></div>
			<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
			<div class="sku-prod" style="width:100%;opacity:0"><b>SKU:</b> <span class="sku-value">{$sku}</span></div>
		HTML;
		$price = $product->get_price_html();
		wc_enqueue_js("
			$(document).on('show_variation', 'form.cart', function( event, variation ) {
				const var_id = $('input.variation_id').val(), prodPrice = $('.summary > p.price'), sku_var = $('.sku_wrapper .sku').text(), currency = '". get_woocommerce_currency_symbol()."';
				$('.product_meta').prepend('<div class=\"woocommerce-product-attributes-item--dimensions\"><span class=\"woocommerce-product-attributes-item__label\"><b>Dimensions: </b></span><span class=woocommerce-product-attributes-item__value></span></div><style>div.woocommerce-product-attributes-item{display:none}div.woocommerce-product-attributes-item:first-child{display:block}</style>');	
				if(variation.price_html) {
					if (document.querySelectorAll('.product .summary').length > 0) {
						$('.summary > p.price').html(variation.price_html);
						$('.summary .product-total-price_sum.price').html(currency + variation.price_html.replace(/[^0-9.]/g, ''));
						$('.summary .product-total-price_sum.price').attr('att_price',variation.price_html.replace(/[^0-9.]/g, ''));
						$('.summary span.product-total-price_caption').hide();
						$('.summary .woocommerce-variation-price').hide();
						$('.summary .quantity .qty').val('1');
						$('table.woocommerce-product-attributes tr.woocommerce-product-attributes-item--dimensions').remove();
					}					
				}
				let size   = $('table.variations select#size').val();
				let width  = $('table.variations select#width').val();
				let height = $('table.variations select#height').val();
				let depth  = $('table.variations select#depth').val();
				//console.log('size (select):', size);
				//console.log('variation:', variation);
				//console.log('variation.attributes:', variation.attributes);
				if (variation.attributes) {
					Object.entries(variation.attributes).forEach(([key, value]) => {
						key = key.replace('attribute_', '').toLowerCase();
						if (value !== '') {
							if (key === 'size')   size = value;
							if (key === 'width')  width = value;
							if (key === 'height') height = value;
							if (key === 'depth')  depth = value;
						}
					});
				}
				let output = '';
				if (size) {
					output = size;
				} else if (height || width || depth) {
					output = [height, width, depth].filter(Boolean).join(' x ');
				}
				//console.log('output:', output);			
				setTimeout(function() {
					if(output) {
						$('.woocommerce-product-attributes-item--dimensions .woocommerce-product-attributes-item__value')
								.text(output)
								.attr('class', 'woocommerce-product-attributes-item__value variation__id-' + var_id)
								.show();
						$('div.woocommerce-product-attributes-item--dimensions').hide();
						setTimeout(function() {	$('div.woocommerce-product-attributes-item--dimensions:last, #product_total_price').show(); }, 200);
					}else{
						$('div.woocommerce-product-attributes-item--dimensions').hide();
					}
					$('span.product-total-price_caption').hide();
					$('.sku-prod .sku-value').text(sku_var);
					$('.sku-prod').css('opacity','1');
				}, 500);
			});
			$(document).on('hide_variation', 'form.cart', function( event, variation ) { 
				const prodPrice = '".wc_get_price_excluding_tax($product)."', currency = '".get_woocommerce_currency_symbol()."';
				$('.woocommerce.single-product .summary .price:not(.product-total-price_sum)').show('fast');
				$('span.product-total-price_caption').hide();
				$('.woocommerce-variation-price').hide();
				$('.quantity .qty').val('1');
				setTimeout(function() {
					$('.woocommerce.single-product .summary .price.product-total-price_sum').hide('fast');
					$('.summary > p.price').html('".$price."');
				}, 1200);
			});
		");
	?>
		<script>
			document.addEventListener("DOMContentLoaded",function() {
				const 	$ = jQuery.noConflict(),					
						currency = '<?=get_woocommerce_currency_symbol()?>';
				$('form.cart .quantity, .single_variation_wrap .quantity').on('click','button', function() {
					setTimeout(function() {
						const 	prodPrice = $('.product-total-price_sum.price').attr('att_price').replace(/[^0-9\.]+/g,""),
								inp_v = $('.quantity .qty').val(), 
								product_t = parseFloat(prodPrice * inp_v);
						$('#product_total_price .price').html(currency + product_t.toFixed(2));
						$('#product_total_price').show();
					}, 200);
				});
			});
		</script>
	<?php
	}
	add_action( 'woocommerce_after_add_to_cart_quantity', 'woocommerce_total_product_price', 31 );

	/**
	 * WooCommerce Quantity +/- buttons
	 */
	function wc_display_quantity_minus() {
	if ( !is_product() ) return;
	echo '<button type="button" class="minus" >-</button>';
	}
	add_action( 'woocommerce_before_quantity_input_field', 'wc_display_quantity_minus' );

	function wc_display_quantity_plus() {
	if ( ! is_product() ) return;
	echo '<button type="button" class="plus" >+</button>';
	}
	add_action( 'woocommerce_after_quantity_input_field', 'wc_display_quantity_plus' );

	function wc_add_cart_quantity_plus_minus() {
		wc_enqueue_js( "
			$('form.cart, .woocommerce-cart-form').on( 'click', 'button.plus, button.minus', function() {
				const 	qty = $( this ).closest( 'form.cart' ).find( '.qty' ),
						val = parseFloat(qty.val()),
						max = parseFloat(qty.attr( 'max' )),
						min = parseFloat(qty.attr( 'min' )),
						step = parseFloat(qty.attr( 'step' ));

				if ( $( this ).is( '.plus' ) ) {
					if ( max && ( max <= val ) ) {
						qty.val( max );
					} else {
						qty.val( val + step );
					}
				} else {
					if ( min && ( min >= val ) ) {
						qty.val( min );
					} else if ( val > 1 ) {
						qty.val( val - step );
					}
				}
			});
	" );
	}
	add_action( 'woocommerce_after_add_to_cart_quantity', 'wc_add_cart_quantity_plus_minus' );

	/*
	* WooCommerce Checkout show product images
	*/
	function ts_product_image_review_order_checkout( $name, $cart_item, $cart_item_key ) {
		if ( ! is_checkout() ) return $name;
		$product = $cart_item['data'];
		$thumbnail = $product->get_image( array( '70', '70' ), array( 'class' => 'alignleft' ) );
		return $thumbnail . $name;
	}
	add_filter( 'woocommerce_cart_item_name', 'ts_product_image_review_order_checkout', 9999, 3 );

	/**
	* Change number of products that are displayed per page (shop page "Shop")
	*/
	function q_loop_shop_per_page( $cols ) {
		// $cols contains the current number of products per page based on the value stored on Options -> Reading
		// Return the number of products you wanna show per page.
		$cols = 8;
		return $cols;
	}
	add_filter( 'loop_shop_per_page', 'q_loop_shop_per_page', 20 );

	/**
	* Replace Add to Cart with Get a Quote button for specific tag
	*/
	function set_quote_only_tag($purchasable, $product) {
		if ( has_term('quote-only', 'product_tag', $product->get_id()) ) {
			return false; // disables Add to Cart
		}
		return $purchasable;
	}
	add_filter('woocommerce_is_purchasable', 'set_quote_only_tag', 10, 2);

	function add_get_quote_button_single() {
		global $product;
		if ( has_term('quote-only', 'product_tag', $product->get_id()) ) {
			echo '<a href="'.get_home_url().'/about/get-a-quote/#' . $product->get_title() . '" class="btn btn-primary get-quote-btn" style="max-width:fit-content">Get Your Quote</a>';
		}
	}
	add_filter('woocommerce_single_product_summary', 'add_get_quote_button_single', 31);

	function add_get_quote_button_loop() {
		global $product;
		if ( has_term('quote-only', 'product_tag', $product->get_id()) ) {
			echo '<a href="'.get_home_url().'/about/get-a-quote/#' . $product->get_title() . '" class="btn btn-primary get-quote-btn" style="max-width:fit-content">Get Your Quote</a>';
		}
	}
	add_filter('woocommerce_after_shop_loop_item','add_get_quote_button_loop', 20);

	/**
	* Add Supplier Logo for Single product if added admin side
	*/
	function woo_supplier_logo() {
		global $product;
		$supplier_logo = get_fields()["supplier_logo"] ?? '';
		$supplier_logo_link =  get_fields()["supplier_logo_link"] ?? '';
		if ( !empty($supplier_logo_link) ) :
			echo '<a href="'.$supplier_logo_link.'" target="_blank">';
		endif;
		if ( !empty($supplier_logo) ) :
			echo '<img src="'.$supplier_logo.'" alt="logo" />';
		endif;
		if ( !empty($supplier_logo_link) ) :
			echo '</a>';
		endif;
	}
	add_filter('woocommerce_single_product_summary','woo_supplier_logo');

	/**
	 * Remove default block patterns.
	 */
	add_action( 'enqueue_block_editor_assets', function() {
		wp_add_inline_style(
			'wp-block-editor',
			'#patterns-navigation-item { display:none !important; }',
			'.woocommerce .products .product .variation-function-added {display:none!important;}'
		);
	});

	// Override theme default specification for product # per row
	function set_custom_products_per_page( $cols ) {
		if ( is_shop() ) {
			return 8; // Set your desired number here if WooCommerce SHop page
		} else {
			return 999;
		}
	}
	add_filter( 'loop_shop_per_page', 'set_custom_products_per_page', 20 );

	// Disable AJAX Cart Fragments
	function dequeue_woocommerce_cart_fragments() {
		if (is_front_page()) wp_dequeue_script('wc-cart-fragments');
	}
	add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11);

	// Automatically send invoice email when new order is created
	add_action('woocommerce_checkout_order_processed', 'send_customer_invoice_automatically', 20, 1);
	function send_customer_invoice_automatically($order_id) {
		$order = wc_get_order($order_id);
		// Only send if the order is in "pending" status    
		if ($order->has_status('pending')) {
			WC()->mailer()->emails['WC_Email_Customer_Invoice']->trigger($order_id);
		}
	}

	//add a small order fee
	function add_small_order_fee( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

		// Set the minimum subtotal to avoid the fee
		$minimum_amount = 200; 
		// Set the fee amount
		$fee = 25; 

		if ( $cart->get_subtotal() < $minimum_amount ) {
			$cart->add_fee( __( 'Small Order Fee', 'woocommerce' ), $fee );
		}
	}
	add_action( 'woocommerce_cart_calculate_fees', 'add_small_order_fee', 20, 1 );

	/**
	 * Replace shipping label with "P.O.A" for flat_rate:8 only
	 */

	/**
	 * Cart & Checkout
	 */
	add_filter( 'woocommerce_cart_shipping_method_full_label', 'replace_shipping_label_with_poa_by_id', 10, 2 );
	function replace_shipping_label_with_poa_by_id( $label, $method ) {

		// Target flat rate method ID 8
		if ( isset( $method->id ) && $method->id === 'flat_rate:8' ) {
			$label = 'P.O.A <small style="display: block;line-height: 1em;">Please get in touch to discuss your order
	</small>';
		}

		return $label;
	}

	/**
	 * Order, Emails, Thank You page
	 */
	add_filter( 'woocommerce_order_shipping_to_display', 'replace_order_shipping_with_poa_by_id', 10, 2 );
	function replace_order_shipping_with_poa_by_id( $shipping, $order ) {

		foreach ( $order->get_shipping_methods() as $shipping_method ) {
			if ( $shipping_method->get_method_id() === 'flat_rate'
				&& $shipping_method->get_instance_id() == 8 ) {

				return 'Shipping P.O.A. Please get in touch to discuss your order';
			}
		}

		return $shipping;
	}

	// Replace shipping message on the cart page
	// Add JS row message to cart totals table
	add_action( 'wp_footer', 'add_shipping_message_row_js' );
	function add_shipping_message_row_js() {
		if ( ! is_cart() ) return;
		?>
		<script>
		document.addEventListener('DOMContentLoaded', function () {
			const cartTotalsTable = document.querySelector('.cart_totals table');
			if (!cartTotalsTable) return;

			if (cartTotalsTable.querySelector('.shipping-checkout-message')) return;

			const row = document.createElement('tr');
			row.className = 'shipping-checkout-message';
			row.innerHTML = `
				<th>Shipping</th>
				<td>Shipping costs will be calculated during checkout.</td>
			`;

			cartTotalsTable.appendChild(row);
		});
		</script>
		<?php
	}

	//disbale shipping at cart only
	add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_on_cart' );
	function disable_shipping_on_cart( $show_shipping ) {
		if ( is_cart() ) {
			return false;
		}
		return $show_shipping;
	}

	/**
	 * Set WooCommerce Ship to a Different Address checkbox to false by default
	 */
	add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );

	/**
	 * Disable WooCommerce styles and scripts on non-WooCommerce pages
	 */
	function disable_woocommerce_assets() {

		// Only run if WooCommerce is active
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// If NOT a WooCommerce page
		if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {

			// Styles
			//wp_dequeue_style( 'woocommerce-general' );
			//wp_dequeue_style( 'woocommerce-layout' );
			//wp_dequeue_style( 'woocommerce-smallscreen' );
			//wp_dequeue_style( 'woocommerce-inline' );

			// Scripts
			//wp_dequeue_script( 'wc-add-to-cart' );
			//wp_dequeue_script( 'wc-cart-fragments' );
			//wp_dequeue_script( 'woocommerce' );
			wp_dequeue_script( 'wc-single-product' );
			//wp_dequeue_script( 'wc-checkout' );
			//wp_dequeue_script( 'wc-add-to-cart-variation' );
			wp_dequeue_script( 'wc-price-slider' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'disable_woocommerce_assets', 99 );

endif;