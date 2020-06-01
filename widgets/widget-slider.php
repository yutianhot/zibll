<?php

add_action('widgets_init', 'widget_register_slider');
function widget_register_slider()
{
    register_widget('widget_ui_slider');
}

class widget_ui_slider extends WP_Widget
{
    function __construct()
    {
        $widget   = array(
            'w_id'     =>  'widget_ui_slider',
            'w_name'     =>  _name('幻灯片'),
            'classname'     => '',
            'description'       => '轻松插入幻灯片',
        );
        parent::__construct($widget['w_id'], $widget['w_name'], $widget);
    }

    function form($instance)
    {
        $defaults = array(
			'in_affix' => '',
            'class'   => 'slide-widget',
            'loop'   => 'on',
            'type'   => '',
            'effect'   => 'slide',
            'blank'  => '',
            'button'   => 'on',
            'pagination'   => 'on',
            'interval'   => 4,
            'null'   => '',
            'auto_height'   => '',
            'pc_height'   => 400,
            'm_height'   => 180,
            'img_ids' => array(),
        );

        $defaults['img_ids'][] = array(
            'title' => '',
            'dec' => '',
            'href'   => '',
            'link' => ''
        );

        $defaults['img_ids'][] = array(
            'title' => '',
            'dec' => '',
            'href'   => '',
            'link' => ''
        );

        $instance = wp_parse_args((array) $instance, $defaults);

        $img_html = '';
        $img_i = 0;

        foreach ($instance['img_ids'] as $category) {
            $_html_a = '<label>幻灯片' . ($img_i + 1) . '-标题<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].title" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][title]" value="' . $instance['img_ids'][$img_i]['title'] . '" /></label>';
            $_html_b = '<label>幻灯片' . ($img_i + 1) . '-简介<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].dec" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][dec]" value="' . $instance['img_ids'][$img_i]['dec'] . '" /></label>';
            $_html_b .= '<label>幻灯片' . ($img_i + 1) . '-链接<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].href" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][href]" value="' . $instance['img_ids'][$img_i]['href'] . '" /></label>';

            $_html_c = '<label>幻灯片' . ($img_i + 1) . '-图片<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].link" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][link]" value="' . $instance['img_ids'][$img_i]['link'] . '" /></label>';

            $_html_d =  '<div class="">' . $_html_c . '<button type="button" class="button ashu_upload_button">选择图片</button><button type="button" class="button delimg_upload_button">移除图片</button><div class="widget_ui_slider_box"><img src="' . $instance['img_ids'][$img_i]['link'] . '"></div></div>';

            $_tt = '<div class="panel"><h4 class="panel-title">幻灯片'.($img_i + 1).'：'.$instance['img_ids'][$img_i]['title'].'</h4><div class="panel-hide panel-conter">';
            $_tt2 = '</div></div>';

            $img_html .= '<div class="widget_ui_slider_g">' .$_tt . $_html_a . $_html_b . $_html_d  .$_tt2 . '</div>';
            $img_i++;
        }

        $add_b = '<button type="button" data-name="' . $this->get_field_name('img_ids') . '" data-count="' . $img_i . '" class="button add_button add_slider_button">添加幻灯片</button>';
        $add_b .= '<button type="button" data-name="' . $this->get_field_name('img_ids') . '" data-count="' . $img_i . '" class="button rem_lists_button">删除幻灯片</button>';
        $img_html .= $add_b;
        //echo '<pre>' . json_encode($instance) . '</pre>';
?>
        <p>
            <label>
                播放速度（每张停留时间多少秒）：
                <input style="width:100%;" id="<?php echo $this->get_field_id('interval');
                                                ?>" name="<?php echo $this->get_field_name('interval');
                            ?>" type="number" value="<?php echo $instance['interval'];
                                            ?>" size="24" />
            </label>
        </p>
        <p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['in_affix'], 'on'); ?> id="<?php echo $this->get_field_id('in_affix'); ?>" name="<?php echo $this->get_field_name('in_affix'); ?>"> 侧栏随动（仅在侧边栏有效）
			</label>
		</p>
      <p>
            <label>
                <input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['loop'], 'on'); ?> id="<?php echo $this->get_field_id('loop'); ?>" name="<?php echo $this->get_field_name('loop'); ?>"> 循环播放
            </label>
        </p>
        <p>
            <label>
                <input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['button'], 'on'); ?> id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name('button'); ?>"> 显示翻页按钮
            </label>
        </p>
        <p>
            <label>
                <input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['pagination'], 'on'); ?> id="<?php echo $this->get_field_id('pagination'); ?>" name="<?php echo $this->get_field_name('pagination'); ?>"> 显示指示器
            </label>
        </p>
        <p>
            <label>
                <input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['blank'], 'on'); ?> id="<?php echo $this->get_field_id('blank'); ?>" name="<?php echo $this->get_field_name('blank'); ?>"> 链接新窗口打开
            </label>
        </p>
        <p>
            <label>
                <input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['auto_height'], 'on'); ?> id="<?php echo $this->get_field_id('auto_height'); ?>" name="<?php echo $this->get_field_name('auto_height'); ?>"> 自动高度
            </label>
            <div>
                <label>
                    <i style="width:100%;color:#f60;">如果勾选了自动高度，下方的固定高度则不生效</i>
                </label>
            </div>

        </p>
        <p>
            <label>
                （固定高度）电脑端高度：
                <input style="width:100%;" id="<?php echo $this->get_field_id('pc_height');
                                                ?>" name="<?php echo $this->get_field_name('pc_height');
                            ?>" type="number" value="<?php echo $instance['pc_height'];
                                            ?>" size="24" />
            </label>
        </p>
        <p>
            <label>
                （固定高度）手机端高度：：
                <input style="width:100%;" id="<?php echo $this->get_field_id('m_height');
                                                ?>" name="<?php echo $this->get_field_name('m_height');
                            ?>" type="number" value="<?php echo $instance['m_height'];
                                            ?>" size="24" />
            </label>
        </p>

        <p>
            <label>
                切换动画：
                <select style="width:100%;" id="<?php echo $this->get_field_id('effect');
                                                ?>" name="<?php echo $this->get_field_name('effect');
                                ?>">
                    <option value="slide" <?php selected('slide', $instance['effect']);
                                            ?>>滑动</option>
                    <option value="fade" <?php selected('fade', $instance['effect']);
                                            ?>>淡出淡入</option>
                    <option value="cube" <?php selected('cube', $instance['effect']);
                                            ?>>3D方块</option>
                    <option value="coverflow" <?php selected('coverflow', $instance['effect']);
                                                ?>>3D滑入</option>
                    <option value="flip" <?php selected('flip', $instance['effect']);
                                            ?>>3D翻转</option>
                </select>
            </label>
        </p>
        <div class="widget_ui_slider_lists">
            <?php echo $img_html; ?>
            <label>
                <input style="vertical-align:-3px;margin-right:4px;" class="checkbox hide" type="checkbox" <?php checked($instance['null'], 'on'); ?> id="<?php echo $this->get_field_id('null'); ?>" name="<?php echo $this->get_field_name('null'); ?>"><a class="button ok_button">应用</a>
            </label>

        </div>
        <p>
            <label>
                <i style="width:100%;color:#f60;">由于WordPress小工具逻辑，会导致后插入的幻灯片偶尔无法保存，请多试几次即可</i>
            </label>
        </p>

        <?php wp_enqueue_media(); ?>
    <?php
    }

    function widget($args, $instance)
    {

        extract($args);

        $defaults = array(
            'class'   => 'slide-widget',
            'in_affix' => '',
            'type'   => '',
            'effect'   => 'slide',
            'interval'   => 4,
            'null'   => '',
            'pc_height'   => 400,
            'm_height'   => 180,
            'img_ids' => array(),
        );

        $defaults['img_ids'][] = array(
            'title' => '',
            'dec' => '',
            'href'   => '',
            'link' => ''
        );

        $defaults['img_ids'][] = array(
            'title' => '',
            'dec' => '',
            'href'   => '',
            'link' => ''
        );

        $instance = wp_parse_args((array) $instance, $defaults);

        $sliders = array(
            'class'   => 'slide-widget',
            'loop'   => !empty($instance['loop'])?true:false,
            'type'   => '',
            'button'   => !empty($instance['button'])?true:false,
            'pagination'   => !empty($instance['pagination'])?true:false,
            'pc_height'   => $instance['pc_height'],
            'm_height'   => $instance['m_height'],
            'effect'   => $instance['effect'],
            'auto_height'   => !empty($instance['auto_height'])?true:false,
            'interval'   => (int) $instance['interval'] * 1000,
        );

        foreach ($instance['img_ids'] as $slide_img) {
            $desc = '<p class="em14">' . $slide_img['title'] . '</p>' . $slide_img['dec'];
            if($slide_img['link']){
                $slide = array(
                'href'   => $slide_img['href'],
                'image'  => $slide_img['link'],
                'blank'  => !empty($instance['blank'])?true:false,
                'desc'     => $desc,
            );
            $sliders['slides'][] = $slide;
        }
        }
		$in_affix = $instance['in_affix'] ? ' data-affix="true"':'';
		echo $in_affix ? '<div'.$in_affix.'>' :'';

       // echo '<pre>'.json_encode($instance).'</pre>';
        zib_get_img_slider($sliders);
		echo $in_affix ? '</div>':'';

    ?>

<?php
    }
}
