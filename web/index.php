<?php

session_start();

// If the session vars aren't set, try to set them with a cookie
if (!isset($_SESSION['user_data']))
{
	if (isset($_COOKIE['user_data']))
	{
		$_SESSION['user_data'] = $_COOKIE['user_data'];
	}
}

if (isset($_SESSION['user_data']))
{
	//echo "logining";
	$login = true;
}
else
{
	$login = false;
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script src="./scriptaculous/lib/prototype.js" type="text/javascript"></script>
		<script src="./scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
		<script src="./slide.js" type="text/javascript"></script>
		<link type="text/css" rel="stylesheet" href="./main.css">
		<link type="text/css" rel="stylesheet" href="./media.css">

		<title>8조</title>
</head>
<body>
	<div id="frame">
	<div id="top_menu">
		<span class="slogan">
			<img src="./img_main/slogan.png">
		</span>
		<ul>
			<?php
			if ($login == false)
			{
				echo '<li><a href="./login.php">로그인</a></li>';
				echo '<li><a href="./join.php">회원가입</a></li>';
			}
			else if ($login == true)
			{
				echo '<li><a href="./logout.php">로그아웃</a></li>';
				echo '<li><a href="./mypage_order.php">주문조회</a></li>';
			}
			?>
			<!--
			<li><a href="./login.php">로그인</a></li>
			<li><a href="./join.php">회원가입</a></li>
		-->
			<li><a href="./index.php">마이페이지</a></li>
		</ul>
	</div>

	<div id="top_menu1">
		<ul>
			<?php
			if ($login == false)
			{
				echo '<li><a href="./login.php">로그인</a></li>';
				echo '<li><a href="./join.php">회원가입</a></li>';
			}
			else if ($login == true)
			{
				echo '<li><a href="./logout.php">로그아웃</a></li>';
				echo '<li><a href="./mypage_order.php">주문조회</a></li>';
			}
			?>
			<!--
			<li><a href="./login.php">로그인</a></li>
			<li><a href="./join.php">회원가입</a></li>
		-->
			<li><a href="./index.php">마이페이지</a></li>
		</ul>
	</div>

	<div id="header">
		<div class="ad-left">
            <img src="./img_main/cstime1.jpg">
        </div>
		<h1>
			<a href="./index.php"><img src="./img_main/main.png" alt="메인사진"></a>
		</h1>
		<div class="ad">
            <img src="./img_main/top-delivery.png">
        </div>
	</div>

	<div id="header1">
		<h1>
			<a href="./index.php"><img src="./img_main/main.png" alt="메인사진"></a>
		</h1>
	</div>

	<div id="menu1">
		<ul>
			<li><a href="./index.php">상품 (Product)</a></li>
			<li><a href="./notice.php">공지사항 (Notice)</a></li>
			<li><a href="./index.php">게시판 (Board)</a></li>
			<li><a href="./index.php">후기 (Review)</a></li>
		</ul>
	</div>
	<div id="menu2">
		<ul id="m1">
			<li><a href="./index.php">상품 (Product)</a></li>
			<li><a href="./notice.php">공지사항 (Notice)</a></li>
		</ul id="m1">
		<ul id="m1">
			<li><a href="./index.php">게시판 (Board)</a></li>
			<li><a href="./index.php">후기 (Review)</a></li>
		</ul id="m1">
		<ul id="m2">
			<li><a href="./index.php">상품 (Product)</a></li>
		</ul>
		<ul id="m2">
			<li><a href="./notice.php">공지사항 (Notice)</a></li>
		</ul>
		<ul id="m2">
			<li><a href="./index.php">게시판 (Board)</a></li>
		</ul>
		<ul id="m2">
			<li><a href="./index.php">후기 (Review)</a></li>
		</ul>
	</div>

	<div id="slide">
		<div>
			<img id="lft" src="./img_main/left_on.png" alt="" onclick="goLeft()">
			<img id="rght2" src="./img_main/right_on.png" alt="" onclick="goRight()" >
		</div>
		<div id="mainimage">
			<img id="img0" src="./img_main/b_1.jpg" style="display:block;" alt="" >
			<img id="img1" src="./img_main/b_2.jpg" style="display:none;" alt="" >
			<img id="img2" src="./img_main/b_3.jpg" style="display:none;" alt="" >
			<img id="img3" src="./img_main/b_4.jpg" style="display:none;" alt="" >

		</div>

		<div>
			<img id="rght" src="./img_main/right_on.png" alt="" onclick="goRight()" >
		</div>
	</div>

	<div id="main-good-list">
            <h2><img src="./img/main-title01.png"></h2>
            <ul>

            <li class="right"><a href="./index.php"><img src="./img_main/main-good02.png"></a></li>
            <li class="left"><a href="./index.php"><img src="./img_main/main-good03.png"></a></li>
            <li class="right"><a href="./index.php"><img src="./img_main/main-good04.png"></a></li>
            <li class="left"><a href="./index.php"><img src="./img_main/main-good05.png"></a></li>
            <li class="right"><a href="./index.php"><img src="./img_main/main-good06.png"></a></li>
            <li class="left"><a href="./index.php"><img src="./img_main/main-good07.png"></a></li>
            <li class="right"><a href="./index.php"><img src="./img_main/main-good13.png"></a></li>
            <li class="left"><a href="./index.php"><img src="./img_main/main-good14.png"></a></li>
            <li class="right"><a href="./index.php"><img src="./img_main/main-good10.png"></a></li>
            <li class="left"><a href="./index.php"><img src="./img_main/main-good11.png"></a></li>

			<!-- 열무김치 <li><a href="/goods/goods_view.php?goodsNo=1000000013"><img src="/data/skin/front/kimchi/img/etc/main-good08.png" /></a></li>
            <li class="right"><a href="/goods/goods_view.php?goodsNo=1000000014"><img src="/data/skin/front/kimchi/img/etc/main-good09.png" /></a></li>-->
            </ul>
	</div>

  <div id="bottom">
		<img src="./img/bottom.png" alt="">
	</div>
	<div id="footer">
		<img src="./img/footer.png" alt="">
	</div>
</div>
</body>
</html>
