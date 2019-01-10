<?php

require_once("dbdata.php");

// Create connection
$conn = new mysqli($servername, $username, $password_db, $dbname);

// Check connection
if ($conn->connect_error)
{
	die("DB Connection failed: " . $conn->connect_error);
}

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

	$user_id = $_SESSION['user_data'];
	$user_id = mysqli_real_escape_string($conn, trim($user_id));

	$sql = 'SELECT * FROM member WHERE id="'.$user_id.'"';
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	// 로그인한 계정의 타입 저장
	$type = $row['mtype'];

	if ($type != 'buyer')
	{
		echo '<script>alert("접근 권한이 없습니다.");location.href="index.php";</script>';
		exit;
	}
}
else
{
	$login = false;

	echo '<script>alert("로그인 후 시도해주세요.");location.href="login.php";</script>';
	exit;
}

//$_GET['pid'], $_GET['num']이 있을 때만 $pno 선언
if(isset($_GET['pid']) && isset($_GET['num']))
{
	$p_id = $_GET['pid'];
	$num = $_GET['num'];

	$p_id = mysqli_real_escape_string($conn, trim($p_id));

	$sql = 'SELECT * FROM product WHERE p_id="'.$p_id.'"';
	$result = $conn->query($sql);

	// p_id가 기본키이므로 1개가 검색되면 존재하는 상품
	if ($result->num_rows == 1)
	{
		// 아래는 화면에 출력해줄 상품,판매자 데이터 (수정불가 데이터)
		$row = $result->fetch_assoc();

		$s_id = $row['s_id'];
		$p_name = $row['name'];
		$p_price = $row['price'];
		$p_measure = $row['measure'];
		$p_img = $row['img_dir'];

		$total_price = $p_price * $num;

		$s_id = mysqli_real_escape_string($conn, trim($s_id));

		$sql = 'SELECT * FROM seller WHERE s_id="'.$s_id.'"';
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();

		$seller_name = $row['name'];
		$seller_phone = $row['phone'];
		$seller_bank = $row['bank'];
		$seller_account = $row['account'];

		// POST 리퀘스트가 존재하면
		// 유저가 페이지의 구매 버튼을 누르면
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$user_name = $_POST['username'];
			$user_phone = $_POST['userphone'];
			$user_address = $_POST['useraddress'];

			if (empty($user_name) || empty($user_phone) || empty(user_address))
			{
				echo '<script>alert("빈칸을 채우시오.");history.back(-1);</script>';
				exit;
			}

/*
			// 주문자 이름 유효성 검사
			if (!preg_match('/\p{Hangul}/u', $name))
			{
				echo '<script>alert("이름은 한글만 사용할 수 있습니다.");history.back(-1);</script>';
				exit;
			}
			if ((strlen($name) < 3) || (strlen($name) > 15))
			{
				echo '<script>alert("이름은 한글 1~5자리입니다.");history.back(-1);</script>';
				exit;
			}

			// 전화번호 유효성 검사
			$phone = str_replace("-", "", $phone);
			if (!preg_match('/^[0-9]{9,12}$/', $phone))
			{
				echo '<script>alert("전화번호를 제대로 입력하십시오.");history.back(-1);</script>';
				exit;
			}
			*/

			// 주소 유효성 검사
			// 주소는 유효성 검사하기 힘들어서 패스

			// 모든 유효성 검사 통과시 DB에 삽입
			$date = date('Y-m-d');

			$num = mysqli_real_escape_string($conn, trim($num));
			$total_price = mysqli_real_escape_string($conn, trim($total_price));

			$sql = "INSERT INTO orders VALUES(null, '".$p_id."', '".$s_id."', '".$user_id."', ".$num.", ".$total_price.", '".$date."', 'order complete', null)";
			$result = $conn->query($sql);

			if ($result)
			{
				// 구매완료 후 마이페이지_주문페이지로 이동
				echo '<script>alert("주문 완료.");location.href="mypage_order.php";</script>';
				exit;
			}
			else
			{
				echo '<script>alert("주문 실패.");history.back(-1);</script>';
				exit;
			}
		}
		else
		{
			// 화면에 출력해줄 구매자 데이터 (이 화면에서만 수정 가능하다.)
			$sql = 'SELECT * FROM buyer WHERE b_id="'.$user_id.'"';
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();

			$user_name = $row['name'];
			$user_phone = $row['phone'];
			$user_address = $row['address'];
		}
	}
	else
	{
		echo '<script>alert("잘못된 접근입니다.");location.href="index.php";</script>';
		exit;
	}

	// Close connection
	$conn->close();
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
	<link type="text/css" rel="stylesheet" href="./notice.css">
	<link type="text/css" rel="stylesheet" href="./order.css">

	<title>8조</title>
</head>
<body>
	<div id="frame">
		<div id="top_menu">
			<span class="slogan">
				<img src="./img_main/slogan.png">
			</span>
			<ul>
				<li><a href="./logout.php">로그아웃</a></li>
				<li><a href="./mypage_order.php">주문조회</a></li>
				<li><a href="./index.php">마이페이지</a></li>
			</ul>
		</div>
		<div id="top_menu1">
			<ul>
				<li><a href="./logout.php">로그아웃</a></li>
				<li><a href="./mypage_order.php">주문조회</a></li>
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
	<form id="frmOrder" name="frmOrder" action="<?php echo $_SERVER['PHP_SELF'].'?pid='.$p_id.'&num='.$num; ?>" method="post" target="ifrmProcess">
		<div id="container">
			<div id="content">
				<div class="contents">
		        <div class="order-page">
	            <div class="step-top">
                <h2>주문서작성</h2>
	            </div>
	            <h3 class="fir">주문상세내역</h3>
	            <div class="table1">
                <table>
                  <thead>
                    <tr>
											<th>이미지</th>
											<th>상품명</th>
											<th>수량</th>
											<th>상품금액</th>
											<th>합계금액</th>
                    </tr>
                  </thead>
                	<tbody>
                    <tr>
                      <td class="this-product">
												<!--
												<span><img src="<?php echo $p_img; ?>" width="40" class="middle" class="imgsize-s" /></span>
											-->
												<img src="<?php echo $p_img; ?>" width="40" class="middle" class="imgsize-s" />
                      </td>
											<td class="ta-c count this-product">
												<?php echo $p_name; ?>
											</td>
											<td class="ta-c this-product">
												<!--
												<strong class="price">6,000원</strong>
											-->
												<?php echo $num.'*'.$p_measure; ?>
											</td>
											<td  class="ta-c">
												<!--
												<strong class="price">6,000원</strong>
											-->
												<?php echo $p_price.'원'; ?>
											</td>
											<td rowspan="1" class="ta-c">
												<?php echo $total_price.'원'; ?>
											</td>
                    </tr>
									</tbody>
                </table>
							</div>
							<!--
							<div class="price-box">
                <div>
									<p>
										<span class="detail">총 <em>1</em>개의 상품금액 <strong>6,000</strong>원</span>
										<span><img src="/data/skin/front/kimchi/img/icon/plus.png" alt="더하기" />배송비 <strong>2,500</strong>원</span>
										<span class="total"><img src="/data/skin/front/kimchi/img/icon/total.png" alt="합계" /><strong>8,500</strong>원</span>
									</p>
                </div>
            	</div>
						-->
							<br/>
							<div class="join-form">
                <fieldset id="fds-order-info">
									<h3>주문자 정보</h3>
									<div class="table1">
										<table>
											<colgroup>
												<col style="width:133px;">
												<col>
											</colgroup>
											<tbody>
												<tr>
													<th class="ta-l">이름</th>
													<td>
														<div class="txt-field" style="width:160px;">
															<input type="text" name="username" value="<?php echo $user_name; ?>" maxlength="20" class="text" />
														</div>
													</td>
												</tr>
												<tr>
													<th class="ta-l">전화번호</th>
													<td>
														<div class="txt-field hs" style="width:160px;">
															<input type="text" id="phoneNum" name="userphone" value="<?php echo $user_phone; ?>" maxlength="20" class="text" />
														</div>
													</td>
												</tr>
												<tr>
													<th class="ta-l">주소</th>
													<td>
														<div class="txt-field hs" style="width:80%">
															<input type="text" id="mobileNum" name="useraddress" value="<?php echo $user_address; ?>" class="text" />
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<h3>판매자 정보</h3>
									<div class="table1">
                    <table>
                      <colgroup>
                        <col style="width:133px;">
                        <col>
                      </colgroup>
                      <tbody>
												<tr>
													<th class="ta-l">판매자</th>
													<td>
														<div class="seller" >
															<?php echo $seller_name; ?>
														</div>
													</td>
                        </tr>
												<tr>
													<th class="ta-l">전화번호</th>
													<td>
														<div class="seller" >
															<?php echo $seller_phone; ?>
														</div>
													</td>
                        </tr>
												<tr>
													<th class="ta-l">입금은행</th>
													<td>
														<div class="seller">
															<?php echo $seller_bank; ?>
														</div>
													</td>
												</tr>
												<tr>
													<th class="ta-l">계좌번호</th>
													<td>
														<div class="seller" >
															<?php echo $seller_account; ?>
														</div>
													</td>
												</tr>
												<tr>
													<th class="ta-l">입금액</th>
													<td>
														<div class="seller">
															<?php echo $total_price.'원'; ?>
														</div>
													</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </fieldset>
            	</div>
        		</div>
				</div>
			</div>
		</div>
		<hr />
    <div class="btn">
      <input type="submit" class="order" value="주문하기">
    </div>
	</form>
	</div>

	<div id="footer">
		<img src="./img_main/footer.png" alt="">
	</div>
</body>
</html>
