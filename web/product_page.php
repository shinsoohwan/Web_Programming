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
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
	$p_id = $_GET['p_id'];


	$conn = mysqli_connect($servername,$username,$password_db, $dbname);

	$result = mysqli_query($conn, "SELECT p_id, s_id, name, category1, category2, price, measure, start_time, content, img_dir FROM product where p_id = $p_id");

	if ($result->num_rows == 1){
    $row = $result->fetch_assoc();

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

		$num = 1;
	}
}
?>
	<form name="buy" action="order.php" method="GET">
		<div class="thumbnail" style="margin-left: 100px">
			<img src="img_goods\<?php echo $img_dir;?>" width="500" height="500" alt="" class="BigImage " style ="float : left; "/>
		</div>
		<h1> 상품 정보 </h1>
			<input type="hidden" name ="pid" value="<?php echo $p_id;?>"/>
		<tbody>
			<div class ="description"style="border: 10px solid #FFFFFF;">
				<span style="font-size:30px;color:#000000;font-weight:bold;">상품명 : </span>
 	 			<span style="font-size:30px;color:#000000;font-weight:bold;"><?php echo $name;?></span>
  		</div>
  		<div class="element"style="border: 10px solid #FFFFFF;">
 	 			<span style="font-size:30px;color:#000000;">판매가 : </span>
 	 			<span style="font-size:30px;color:#000000;"><?php echo $price;?>원</span>
  		</div>
			<div class="quantity"style="border: 10px solid #FFFFFF;">
 	 			<span style="font-size:30px;color:#000000;">단위 : </span>
 	 			<span style="font-size:30px;color:#000000;"><?php echo $measure;?></span>
  		</div>
			<div>
			<span style="font-size:30px;color:#000000; " >수량 : </span>
				<span id='Productcount' >
					<input id="count" name="num" style="width: 50px;height: 30px;border: 1px solid #BCBCBC; font-size:30px;"
									value="<?php echo $num;?>" type="text"  />
				</span>
			</div>

<!-- 구매자인지 검사 -->

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

	if ($type == 'buyer')
	{
?>



			<div class="purchase" style="border: 30px solid #FFFFFF;">
				<a href ="./order.php?pid=<?php echo $p_id;?>&num=<?php echo $num;?>?">
				<input type="submit" style="width: 200px;height: 50px;" value=" 구 매 " />
			</a>
			</div>
		</tbody>
		</form>
<?php
	}
}
?>


<!-- 판매자 본인인지 검사 -->

<?php
if (isset($_SESSION['user_data']))
{
	$user_id = $_SESSION['user_data'];

	if($user_id == $s_id){

?>
		<div>
			<p class="list_modufy" style="border: 30px solid #FFFFFF;float: left;">
				<a href ="./product_update.php?p_id=<?php echo $p_id;?>">
					<input type="submit" style="width: 100px;height: 30px;" value=" 상품수정 " />
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
