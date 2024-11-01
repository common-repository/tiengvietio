<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
function TiengViet_adminPanel() {
	$token = get_option('TVtoken');
	if(isset($_GET['settings-updated'])) {
		$msgSuccess = '<div id="message" class="updated"><p><strong>Đã lưu</strong></p></div>';
	} else $msgSuccess = '';
	if($token){
		$url = TVROOTAPIURL."info.php";		$data = array(			"body" => json_encode(array(
				'token' => sanitize_text_field($token)
			)),			'timeout'     => '5',			'redirection' => '5',			'httpversion' => '1.0',			'blocking'    => true,
			'data_format' => 'body',			'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),			'cookies'     => array(),		);
		$json = wp_remote_post($url,$data);
	//	var_dump($json);
		$json = json_decode($json['body'],true);
		if($json['name']) $Hello = "<h3>Xin chào {$json['name']},</h3>";
		$naptien = "{$Hello} {$json['message']}";
		if($json['priceTable']) $priceTable = $json['priceTable'];
		if($json['invoice']){
			$showtien ="<div class=\"TVInvoice\">{$json['invoice']}</div>";
		}
	}	$allowed_html = array(
		'br'=>array('class'=>array()),		'div'=>array(			'class'=>array(),
			'style'=>array(),			'id'=>array()		),
		'span'=>array(
			'class'=>array(),
			'id'=>array(),
			'data-rel'=>array(),
			'style'=>array()
		),		'p'=> array(			'class'=>array()		),		'h1'=> array(			'class'=>array()		),		'h2'=> array(			'class'=>array()		),		'h3'=> array(			'class'=>array()		),		'h4'=> array(			'class'=>array()		),		'h5'=> array(			'class'=>array()		),		'h6'=> array(			'class'=>array()		),		'strong'=> array(			'class'=>array(),
			'style'=>array()		),		'a'=> array(			'href'  => array(),			'title' => array()		),		'img'=> array(			'style'=>array(),
			'alt'=>array(),			'src'=>array()		),		'table'=> array(			'class'=>array()		),		'tr'=> array(			'class'=>array()		),		'td'=> array(			'class'=>array()		),
		'input'=> array(
			'class'=>array(),
			'type'=>array(),
			'style'=>array(),
			'value'=>array(),
			'data-rel'=>array()
		)	);
?>
<div id="TVAuditPlugin">
<div class="wrap wrap-admin" style="float:left;">
	<h2>Cài đặt plugin TiengVietIO</h2>
	<?php echo wp_kses($msgSuccess,$allowed_html); ?>
	<form method="post" action="options.php">
		<?php settings_fields('TVtoken'); ?>
		<div class="form-field form-required term-name-wrap">
			<label for="tag-name">Nhập token (Lấy tại: <a href="https://my.tiengviet.io/api/" target="_blank">https://my.tiengviet.io/api/</a>)</label>
			<p><input type="text" name="TVtoken" value="<?php echo sanitize_text_field($token); ?>" /></p>
		</div>
		<?php echo get_submit_button(); ?>
	</form>
	<div id="TVLeft">
		<div style="padding-right:20px;">
			<?php echo wp_kses($showtien, $allowed_html); ?>
		</div>
	</div>
	<div id="TVRight">
		<?php echo wp_kses($naptien, $allowed_html); ?>
		<?php echo wp_kses($priceTable, $allowed_html); ?>
	</div>
</div>
</div>
<?php } ?>