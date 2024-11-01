<?php
function TiengViet_meta_box_callback(){
	echo <<<vnz
		<p><input data-wp-taxonomy="TVkeyword" type="text" id="TVkeyword" style="width:100%"></p>
		<div class="ketqua_lsi"></div> 
		<p><span class="button button-primary button-hero getLSIKeyword">Bước 1: Lấy từ khóa LSI (30 Xu/ Lần)</span></p>
		<div class="ketqua_audit"></div>
		<p><span class="button button-primary button-hero TVaudit">Bước 2: Xem kết quả tối ưu (Free)</span></p>
vnz;
}
function TiengViet_add_meta_box_custom($id,$title,$function){
	?>
	 <div id="<?php echo sanitize_text_field($id); ?>" class="postbox wpseo-taxonomy-metabox-postbox">
	<div class="postbox-header"><h2 class="hndle ui-sortable-handle"><?php echo sanitize_text_field($title);?></h2></div>
		<div class="inside"> 
			<?php $function(); ?>
		</div>
	</div>
<?php
}
function TiengViet_meta_box_custom() {
	TiengViet_add_meta_box_custom('TVAuditPlugin','Tối ưu hóa nội dung bởi TiengViet.IO','TiengViet_meta_box_callback');
}
function TiengViet_add_meta_box() {
    $screens = array('post','category','page','product');
    add_meta_box(
        'TVAuditPlugin',
        __( 'Tối ưu hóa nội dung bởi TiengViet.IO', 'TVAuditPlugin_textdomain' ),
			'TiengViet_meta_box_callback',
        $screens, $context = 'advanced'
    );
}
function TiengViet_custom_script_load(){
 $url = admin_url('admin-ajax.php');
 echo <<<vnz
 <script>
	jQuery(document).on("click",".getLSIKeyword",function(){
		key = jQuery('#TVkeyword').val();
		if(jQuery("#content").length != 0) content = tinymce.get("content").getContent({format:'text'}).toLowerCase();	
			else if(jQuery("#description").length != 0)  content = tinymce.get("description").getContent({format:'text'}).toLowerCase();
		jQuery.ajax({
			type : "post",
			dataType : "json",
			url : "{$url}", 
			data : {
				action: "TiengViet_getLSIKeyword",
				key:key
			},
			context: this,
			beforeSend: function(){
				jQuery('.getLSIKeyword').text("Loading...!");
			},
			success: function(response) {
				
				jQuery('.ketqua_lsi').html(response.message);
				if(response.code==200) jQuery('.getLSIKeyword').hide();
				else jQuery('.getLSIKeyword').text('Bước 1: Lấy từ khóa LSI (30 Xu/ Lần)');
			},
			error: function( jqXHR, textStatus, errorThrown ){
				console.log( 'The following error occured: ' + textStatus, errorThrown );
			}
		})
	});
	function importantKey(content){
		j=0;
		i=0;
		tong = jQuery("span._tukhoa_tach").length;
		limit = Math.ceil(tong/3);
		jQuery("span._tukhoa_tach").each(function(){
			val = jQuery(this).text();
			if(i<=limit){
				if(content.split(val).length>1){
					j++;
				}
			}
			i++;
		});
		return Math.ceil(j/limit*100);
	}
	function keyInContent(content){
		j=0;
		i=0;
		jQuery("span._tukhoa_tach").removeClass("active-key");
		jQuery("span._tukhoa_tach").each(function(){
			val = jQuery(this).text();
			if(content.split(val).length>1){
				j++;
				jQuery(this).addClass("active-key");
			}
			i++;
		});
		return Math.ceil(j/i*100);
	}
	jQuery(document).on("click",".copy",function(){
	  var copyText = jQuery(".input-copy");
	  copyText.select();
	  jQuery(".copy-text").text("Đã sao chép");
	  document.execCommand("copy");
	});
	function auditContentCheck(){
		if(jQuery("#content").length != 0) content = tinymce.get("content").getContent().toLowerCase();	
			else if(jQuery("#description").length != 0) content = tinymce.get("description").getContent().toLowerCase();
		phantram = keyInContent(content);
		return phantram;
	}
	jQuery(document).on("click",".TVaudit",function(){
		if(jQuery("#content").length != 0) content = tinymce.get("content").getContent().toLowerCase();	
			else if(jQuery("#description").length != 0) content = tinymce.get("description").getContent().toLowerCase();	
		total_key = auditContentCheck();
		goodkey = importantKey(content);
		post_title = jQuery('#hidden_wpseo_title').val();
		if(!post_title)post_title = jQuery('.serp-title').text();
		if(!post_title)post_title = jQuery('#rank_math_title').val();
		if(!post_title)post_title = jQuery('#title').val();
		if(!post_title) post_title = jQuery('#name').val();
		key = jQuery('#TVkeyword').val();
		slug = jQuery('#editable-post-name').text();
		description = jQuery('#yoast_wpseo_metadesc').val();
		if(!description) description = jQuery('.serp-description').text();
		if(!description) description = jQuery('#rank_math_description').val();
			if(!description) description = jQuery('#hidden_wpseo_desc').val();
		jQuery.ajax({
			type : "post",
			dataType : "json",
			url : "{$url}", 
			data : {
				action: "TiengViet_getAudit",
				key:key,
				total_key:total_key,
				goodkey:goodkey,
				title: post_title,
				slug:slug,
				content:content,
				description:description
			},
			context: this,
			beforeSend: function(){
				jQuery('.TVaudit').text("Loading...!");
				jQuery('.ketqua_audit').html("Loading...!");
			},
			success: function(response) {
				
				jQuery('.ketqua_audit').html(response.message);
				jQuery('.TVaudit').text("Bước 2: Xem kết quả tối ưu (Free)");
			},
			error: function( jqXHR, textStatus, errorThrown ){
				console.log( 'The following error occured: ' + textStatus, errorThrown );
			}
		})
	});
	jQuery(document).on("click",".deposit",function(){
		id = jQuery(this).attr("data-rel");
		thisDom = jQuery(this);
		jQuery.ajax({
			type : "post",
			dataType : "json",
			url : "{$url}", 
			data : {
				action: "TiengViet_Deposit",
				id:id
			},
			context: this,
			beforeSend: function(){
				thisDom.text("Loading...!");
				jQuery('.TVInvoice').html("Loading...!");
			},
			success: function(response) {
				
				jQuery('.TVInvoice').html(response.message);
				thisDom.text("Đã tạo thành công");
			},
			error: function( jqXHR, textStatus, errorThrown ){
				console.log( 'The following error occured: ' + textStatus, errorThrown );
			}
		})
	});
	jQuery(document).on("click",".saveCoupon",function(){
		id = jQuery('.coupon').attr("data-rel");
		value = jQuery('.coupon').val();
		thisDom = jQuery(this);
		if(confirm("Bạn chắc chắn áp mã giảm giá này?")){
			jQuery.ajax({
				type : "post",
				dataType : "json",
				url : "{$url}", 
				data : {
					action: "TiengViet_Coupon",
					id:id,
					coupon:value
				},
				context: this,
				beforeSend: function(){
					thisDom.val("Loading...!");
				},
				success: function(response) {
					window.location.href = window.location.href;
				},
				error: function( jqXHR, textStatus, errorThrown ){
					console.log( 'The following error occured: ' + textStatus, errorThrown );
				}
			});
		}
	});
	
 </script>
vnz;
}
function TiengViet_admin_style(){
    wp_register_style( 'custom_wp_admin_css',  plugins_url('/css/style.css',__FILE__),  true, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css' );
}
function TiengViet_Coupon() {
	$token = get_option('TVtoken');
	if(!$token) die(json_encode(array(
		"code"=>404,
		"message"=>"Token không tồn tại. Vui lòng thêm token của TiengVietIO ở mục cài đặt plugin hoặc xem thêm tại: <a href='https://my.tiengviet.io/api/' target='_blank'>https://my.tiengviet.io/api/</a>",
	), JSON_UNESCAPED_UNICODE));
	$data = array(
		'body' => json_encode(array(
			'id' => sanitize_text_field($_POST['id']),
			'coupon' => sanitize_text_field($_POST['coupon']),
			'token' => sanitize_text_field($token)
		)),
		'timeout'     => '5',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'data_format' => 'body',
		'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
		'cookies'     => array(),
	);
	wp_remote_post(TVROOTAPIURL."coupon/",$data);
    die();
}
function TiengViet_Deposit() {
	$token = get_option('TVtoken');
	if(!$token) die(json_encode(array(
		"code"=>404,
		"message"=>"Token không tồn tại. Vui lòng thêm token của TiengVietIO ở mục cài đặt plugin hoặc xem thêm tại: <a href='https://my.tiengviet.io/api/' target='_blank'>https://my.tiengviet.io/api/</a>",
	), JSON_UNESCAPED_UNICODE));
	$data = array(
		'body' => json_encode(array(
			'id' => sanitize_text_field($_POST['id']),
			'token' => sanitize_text_field($token)
		)),
		'timeout'     => '5',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'data_format' => 'body',
		'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
		'cookies'     => array(),
	);
	$msg = wp_remote_post(TVROOTAPIURL."deposit/",$data);
	echo json_encode(json_decode($msg['body'],true));
    die();
}
function TiengViet_getAudit() {
	$token = get_option('TVtoken');
	if(!$token) die(json_encode(array(
		"code"=>404,
		"message"=>"Token không tồn tại. Vui lòng thêm token của TiengVietIO ở mục cài đặt plugin hoặc xem thêm tại: <a href='https://my.tiengviet.io/api/' target='_blank'>https://my.tiengviet.io/api/</a>",
	), JSON_UNESCAPED_UNICODE));
	$allowed_html = array(
		'br'=>array('class'=>array()),
		'div'=>array(
			'class'=>array(),
			'style'=>array(),
			'id'=>array()
		),
		'span'=>array(
			'class'=>array(),
			'id'=>array(),
			'data-rel'=>array(),
			'style'=>array()
		),
		'p'=> array(
			'class'=>array()
		),
		'h1'=> array(
			'class'=>array()
		),
		'h2'=> array(
			'class'=>array()
		),
		'h3'=> array(
			'class'=>array()
		),
		'h4'=> array(
			'class'=>array()
		),
		'h5'=> array(
			'class'=>array()
		),
		'h6'=> array(
			'class'=>array()
		),
		'strong'=> array(
			'class'=>array(),
			'style'=>array()
		),
		'a'=> array(
			'href'  => array(),
			'title' => array()
		),
		'img'=> array(
			'style'=>array(),
			'alt'=>array(),
			'title'=>array(),
			'src'=>array()
		),
		'table'=> array(
			'class'=>array()
		),
		'tr'=> array(
			'class'=>array()
		),
		'td'=> array(
			'class'=>array()
		),
		'input'=> array(
			'class'=>array(),
			'type'=>array(),
			'style'=>array(),
			'value'=>array(),
			'data-rel'=>array()
		)
	);
	$data = array(
		'body' => json_encode(array(
			'key'=>sanitize_text_field($_POST['key']),
			'content' => wp_kses($_POST['content'],$allowed_html), // Xem xét lại
			'title' => sanitize_text_field($_POST['title']),
			'slug' => sanitize_text_field($_POST['slug']),
			'goodkey' => sanitize_text_field($_POST['goodkey']),
			'total_key' => sanitize_text_field($_POST['total_key']),
			'description' => sanitize_text_field($_POST['description']),
			'm' => sanitize_text_field($_SERVER['HTTP_HOST']),
			'token' => sanitize_text_field($token)
		)),
		'timeout'     => '5',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'data_format' => 'body',
		'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
		'cookies'     => array(),
	);
	$msg = wp_remote_post(TVROOTAPIURL."audit/",$data);
	echo json_encode(json_decode($msg['body'],true));
    die();
}
function TiengViet_getLSIKeyword() {
	$token = get_option('TVtoken');
	if(!$token) die(json_encode(array(
		"code"=>404,
		"message"=>"Token không tồn tại. Vui lòng thêm token của TiengVietIO ở mục cài đặt plugin hoặc xem thêm tại: <a href='https://my.tiengviet.io/api/' target='_blank'>https://my.tiengviet.io/api/</a>",
	), JSON_UNESCAPED_UNICODE));
	$key = sanitize_text_field($_POST['key']);
	$data = array(
		'body' => json_encode(array(
			'key'=>$key,
			'token' => sanitize_text_field($token)
		)),
		'timeout'     => '5',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'data_format' => 'body',
		'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
		'cookies'     => array(),
	);
	$msg = wp_remote_post(TVROOTAPIURL."lsikeyword/",$data);
	echo json_encode(json_decode($msg['body'],true));
    die();
}
function TiengViet_adminPanelOption() {
        register_setting( 'TVtoken', 'TVtoken' );
}
function TiengViet_adminPanel_settings() {
        add_menu_page('TiengVietIO', 'TiengVietIO', 'administrator', __FILE__, 'TiengViet_adminPanel',plugins_url('/images/logo-plugin.png', __FILE__), 5);
        add_action( 'admin_init', 'TiengViet_adminPanelOption' );
} 
?>