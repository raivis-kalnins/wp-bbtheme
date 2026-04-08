<?php 
//ini_set('display_errors', 1);
//var_dump(get_fields('options'));
/**
 * Add short code load more loop [load_more_cards]
 */
$btnMoreItems = get_fields('option')['loadmore_list'] ?? '';

if ( !empty($btnMoreItems) ) :

	foreach( $btnMoreItems as $btnMoreItem ) :

		$btn_more_id = stripslashes($btnMoreItem["loadmore_id"]) ?? '';
		$btn_more_col = stripslashes($btnMoreItem["loadmore_col"]) ?? '';
		$btn_more_hov_col = stripslashes($btnMoreItem["loadmore_hov_col"]) ?? '';
		$btn_more_items = stripslashes($btnMoreItem["loadmore_items"]) ?? '';
		$btn_more_items_load = stripslashes($btnMoreItem["loadmore_items_load"]) ?? '';
		$btn_more_parent = stripslashes($btnMoreItem["loadmore_parent"]) ?? '';
		$btn_more_child = stripslashes($btnMoreItem["loadmore_child"]) ?? '';
		$btn_more_class = stripslashes($btnMoreItem["loadmore_class"]) ?? '';
		$btn_more_txt = stripslashes($btnMoreItem['loadmore_name']) ?? '';
		$load_more_script = stripslashes('<style>'.$btn_more_child.'{display:none}#loadMore:hover{background-color:'.$btn_more_hov_col.' !important}</style><script id="load-more-js">
			document.addEventListener("DOMContentLoaded",function(){
				const $=jQuery.noConflict(),items = $("'.$btn_more_child.'").length, acf_items = '.$btn_more_items.'; //console.log(acf_items);
				if (  acf_items < items ) {
					$("#loadMore").show();
				} else {
					$("#loadMore").hide();
				}
				setTimeout(function() {
					$("'.$btn_more_child.'").slice(0,'.$btn_more_items.' + 1).show();
				}, 500);
				$(document).on("change", "'.$btn_more_parent.'", function() {
					setTimeout(function() {
						$("'.$btn_more_child.'").slice(0,'.$btn_more_items.').show();
					}, 500);
				});
				$("#loadMore").on("click", function(e){
					$("'.$btn_more_child.':hidden").slice(0,'.$btn_more_items_load.').slideDown();
					if( $("'.$btn_more_child.':hidden").length == 0 ) {
						$("#loadMore").text("No Content").addClass("noContent").hide();
					}
					e.preventDefault();
				});
			});
		</script>');
		$loop =  $loop ?? '';
		$loop =	'<div id="loadMore" class="'.$btn_more_class.'" style="background-color:'.$btn_more_col.' !important;margin:15px auto">'.$btn_more_txt.'</div>'.$load_more_script;
		$eachLodeMore = '<div class="short-component acf-form acf-form-'.$btn_more_id.'" style="display:flex">'.$loop.'</div>';

		add_shortcode( 'load_more_'.$btn_more_id, function() use ($eachLodeMore) {
			return $eachLodeMore;
		});

	endforeach;

endif;