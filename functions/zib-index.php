<?php

function zib_index_slide(){
	$is = _pz('index_slide_sort') ? _pz('index_slide_sort') : '4';
	$loop = _pz('index_slide_loop_s',true);
	$type = _pz('index_slide_moshi');
	$effect = _pz('index_slide_effect');
	$interval = _pz('index_slide_interval') * 1000 ;

	$args = array(
		'class'   => 'slide-index',
		'loop'   => $loop,
		'type'   => $type,
		'button'   => _pz('index_slide_show_button',true),
		'pagination'   => _pz('index_slide_show_pagination',true),
		'auto_height'   => _pz('index_slide_auto_height'),
		'm_height'   => _pz('index_slide_height_m',200),
		'pc_height'   => _pz('index_slide_height',350),
		'effect'   => $effect,
		'interval'   => $interval
		);

		if(_pz('index_slide_position')=='header'){
			$args['class'] = 'slide-index slide-header';
		}
	for ($i = 1; $i <= $is; $i++) {
		$desc = '<p class="em14">'._pz('index_slide_title_'.$i).'</p>'._pz('index_slide_desc_'.$i);
		$slide = array(
			'href'   => _pz('index_slide_href_'.$i),
			'image'  => _pz('index_slide_src_'.$i),
			'blank'  => _pz('index_slide_blank_'.$i),
			'desc'	 => $desc,
			);
		$args['slides'][] = $slide;
	}
	zib_get_img_slider($args);
}

function zib_index_tab($nav='nav'){

	$home_list_num=_pz('home_list_num')?_pz('home_list_num'):'4';
	for ($i=2; $i <= $home_list_num; $i++) {
		$cat_id = _pz('home_list'.$i.'_cat');
		$cat_s  = _pz('home_list'.$i.'_s');
		if($cat_id&&$cat_s){
			$g_c_t = get_cat_name($cat_id);
			$cat_t = _pz('home_list'.$i.'_t')?_pz('home_list'.$i.'_t'):$g_c_t;
			if($nav=='nav'){
			echo '<li><a data-toggle="tab" data-ajax="'.get_category_link($cat_id).'" href="#index-tab-'.$i.'">'.$cat_t.'</a></li>';
			}elseif ($nav=='content') {
			echo '<div class="ajaxpager tab-pane fade" id="index-tab-'.$i.'">';
			echo '<span class="post_ajax_trigger"><a class="ajax_load ajax-next ajax-open" href="'.get_category_link($cat_id).'"></a></span>';
			echo '<div class="post_ajax_trigger">'.zib_placeholder().'</div>';
			echo '</div>';
			}

		}
	}

}

