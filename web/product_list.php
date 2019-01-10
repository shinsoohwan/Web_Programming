<?php

require_once("dbdata.php");

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
		<link type="text/css" rel="stylesheet" href="./main2.css">
		<link type="text/css" rel="stylesheet" href="./media2.css">

		<title>돌재농원</title>

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
				<li><a href="./mypage_personal.php">마이페이지</a></li>
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
				<li><a href="./index.php">농장소개 (Introduce)</a></li>
				<li><a href="./product_list.php">상품 (Product)</a></li>
				<li><a href="./notice.php">공지사항 (Notice)</a></li>
			</ul>
		</div>
		<div id="menu2">
			<ul id="m1">
				<li><a href="./index.php">농장소개 (Introduce)</a></li>

			</ul id="m1">
			<ul id="m1">
				<li><a href="./product_list.php">상품 (Product)</a></li>
				<li><a href="./notice.php">공지사항 (Notice)</a></li>
			</ul id="m1">
			<ul id="m2">
				<li><a href="./index.php">농장소개</a></li>

			</ul id="m2">
			<ul id="m2">
				<li><a href="./product_list.php">상품</a></li>
			</ul>
			<ul id="m2">
				<li><a href="./notice.php">공지사항</a></li>
			</ul>

		</div>

<?php

	//echo "start";

	$conn = mysqli_connect($servername,$username,$password_db, $dbname);

	if (mysqli_connect_errno($conn))
	{
		echo "실패";
	}
	else
	{

		$result = mysqli_query($conn, "select * From product Order by p_id Desc");
		$i = 0;
			while ($row = mysqli_fetch_array($result))
			{
				$p_id = $row['p_id'];
  			$s_id = $row['s_id'];
  			$name = $row['name'];
				$category1 = $row['category1'];
				$category2 = $row['category2'];
				$price = $row['price'];
				$measure = $row['measure'];
				$start_time = $row['start_time'];
				$content = $row['content'];
				$img_dir = $row['img_dir'];


?>
	<div alt="" style="float: left; width:30%; height : 400px; border : 2px solid black; margin-top: 10px;margin-bottom : 10px">
		<a href ="./product_page.php?p_id=<?php echo $p_id;?>" style="text-decoration:none;">
			<div class="thumbnail" style="border : 1px solid red">
				<img src="img_goods\<?php echo $img_dir;?>" alt="" style="width:80%; height : 300px"/>
			</div>

			<div class ="description">
	      <span style="font-size:25px;color:#000000;  margin-left:30px"><?php echo $name;?></span>
			</div>
			<div class="element">
	      <span style="font-size:20px;color:#000000; margin-left:30px"><?php echo $price;?></span>
			</div>
		</a>
	</div>

	<div alt="" style="float: left; width:3%; height : 400px; ">
	</div>

<?php
			}
	}
?>

<!--  판매자인지 검사  -->

<?php
if (isset($_SESSION['user_data']))
{
	//echo "logining";
	$login = true;

	$user_id = $_SESSION['user_data'];

	$sql = 'SELECT * FROM member WHERE id="'.$user_id.'"';
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	// 로그인한 계정의 타입 저장
	$type = $row['mtype'];

	if ($type == 'seller')
	{

?>

<div class="list_control">
	<p class="list_update" style="border: 30px solid #FFFFFF;float: right;">
		<a href ="./product_new.php">
			<input type="submit" style="width: 100px;height: 30px;" value=" 상품추가 " />
		</a>
	</p>
</div>

<?php
	}
}
?>

  	<div id="footer">
			<img src="./img_main/bottom.png" alt="">
		</div>
		<div id="footer">
			<img src="./img_main/footer.png" alt="">
		</div>
	</body>
</html>
