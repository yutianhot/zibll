<?php 
/**
 * 子比主题
 * Zibll Theme
 * 官方网站：https://www.zibll.com/
 * 作者QQ：770349780
 * 感谢您使用子比主题，主题源码有详细的注释，支持二次开发
 * 如您需要定制功能、或者其它任何交流欢迎加QQ
 */

if(strlen($_SERVER['REQUEST_URI']) > 384 ||
    strpos($_SERVER['REQUEST_URI'], "eval(") ||
	strpos($_SERVER['REQUEST_URI'], "base64")) {
		@header("HTTP/1.1 414 Request-URI Too Long");
		@header("Status: 414 Request-URI Too Long");
		@header("Connection: Close");
		@exit;
}
//通过QUERY_STRING取得完整的传入数据，然后取得url=之后的所有值，兼容性更好
$t_url = preg_replace('/^url=(.*)$/i','$1',$_SERVER["QUERY_STRING"]);

//数据处理
if(!empty($t_url)) {
    //判断取值是否加密
    if ($t_url == base64_encode(base64_decode($t_url))) {
        $t_url =  base64_decode($t_url);
    }
    //对取值进行网址校验和判断
    preg_match('/^(http|https|thunder|qqdl|ed2k|Flashget|qbrowser):\/\//i',$t_url,$matches);
	if($matches){
	    $url=$t_url;
	    $title='页面加载中,请稍候...';
	} else {
	    preg_match('/\./i',$t_url,$matche);
	    if($matche){
	        $url='http://'.$t_url;
	        $title='页面加载中,请稍候...';
	    } else {
	        $url = 'http://'.$_SERVER['HTTP_HOST'];
	        $title='参数错误，正在返回首页...';
	    }
	}
} else {
    $title = '参数缺失，正在返回首页...';
    $url = 'http://'.$_SERVER['HTTP_HOST'];
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="robots" content="noindex, nofollow" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<noscript><meta http-equiv="refresh" content="1;url='<?php echo $url;?>';"></noscript>
<script>
function link_jump()
{
    //禁止其他网站使用我们的跳转页面
    var MyHOST = new RegExp("<?php echo $_SERVER['HTTP_HOST']; ?>");
    if (!MyHOST.test(document.referrer)) {
         location.href="http://" + MyHOST;
    }
    location.href="<?php echo $url;?>";
}
//延时1S跳转，可自行修改延时时间
setTimeout(link_jump, 1500);
//延时50S关闭跳转页面，用于文件下载后不会关闭跳转页的问题
setTimeout(function(){window.opener=null;window.close();}, 50000);
</script>
<title><?php echo $title;?></title>
<style type="text/css">
body{background:#fff}

.qjdh_no6 {
    transform:scale(1) translateY(-30px);
}
.qjdh_no6>div:nth-child(2) {
    -webkit-animation-delay:-.4s;
    animation-delay:-.4s
}
.qjdh_no6>div:nth-child(3) {
    -webkit-animation-delay:-.2s;
    animation-delay:-.2s
}
.qjdh_no6>div {
    position:absolute;
    top:0;
    left:-30px;
    margin:2px;
    margin:0;
    width:15px;
    width:60px;
    height:15px;
    height:60px;
    border-radius:100%;
    background-color:#ff3cb2;
    opacity:0;
    -webkit-animation-fill-mode:both;
    animation-fill-mode:both;
    -webkit-animation:ball-scale-multiple 1s .5s linear infinite;
    animation:ball-scale-multiple 1s .5s linear infinite
}
@-webkit-keyframes ball-scale-multiple {
    0% {
        opacity:0;
        -webkit-transform:scale(0);
        transform:scale(0)
    }
    5% {
        opacity:1
    }
    to {
        -webkit-transform:scale(1);
        transform:scale(1)
    }
}
@keyframes ball-scale-multiple {
    0%, to {
        opacity:0
    }
    0% {
        -webkit-transform:scale(0);
        transform:scale(0)
    }
    5% {
        opacity:1
    }
    to {
        opacity:0;
        -webkit-transform:scale(1);
        transform:scale(1)
    }
}	

@keyframes ball-s {
    0%, to {
        opacity:0;
        transform:scale(0)
    }
    to {
        opacity:1;
        transform:scale(1)
    }
}

@keyframes ball-s2 {
    0% {
        opacity:0;
    }30% {
        opacity:0;
    }
    to {
        opacity:1;
    }
}
</style>
</head>
<body>

        <div style="position:fixed;animation:ball-s .5s 0s ease-out;top:-60px;left:0;bottom:0;right:0;display:flex;align-items:center;justify-content:center">
			    <div class="qjdh_no6">
			        <div></div>
			        <div></div>
			        <div></div>
			    </div> 
        </div>
			  <div style="position:fixed;top:60px;left:0;bottom:0;color: #f156b4;animation:ball-s2 .8s cubic-bezier(0.36, 0.29, 0.62, 1.36);right:0;display:flex;align-items:center;justify-content:center;">页面加载中，请稍候...</div>

</body>
</html>
