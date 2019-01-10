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
}
else
{
	$login = false;

	echo '<script>alert("로그인 후 시도해주세요.");location.href="login.php";</script>';
	exit;
}

// $_GET['oid']가 있을 때만
if(isset($_GET['oid']))
{
	$o_id = $_GET['oid'];

	$o_id = mysqli_real_escape_string($conn, trim($o_id));

	$sql = 'SELECT * FROM orders WHERE o_id="'.$o_id.'"';
	$result = $conn->query($sql);

	// o_id가 기본키이므로 1개가 검색되면 존재하는 상품
	if ($result->num_rows == 1)
	{
    $row = $result->fetch_assoc();

    $p_id = $row['p_id'];
    $b_id = $row['b_id'];
    $s_id = $row['s_id'];

    if ($type == 'buyer')
    {
      if ($user_id != $b_id)
      {
        echo '<script>alert("잘못된 접근입니다.");location.href="index.php";</script>';
        exit;
      }
    }
    else if ($type == 'seller')
    {
      if ($user_id != $s_id)
      {
        echo '<script>alert("잘못된 접근입니다.");location.href="index.php";</script>';
        exit;
      }
    }

    $numbers = $row['numbers'];
    $total_price = $row['price'];
    $order_time = $row['order_time'];
    $status = $row['status'];
    $delivery = $row['delivery'];

    $sql = 'SELECT * FROM product WHERE p_id="'.$p_id.'"';
    $result = $conn->query($sql);

    if ($result->num_rows == 1)
    {
      $row = $result->fetch_assoc();

      $p_name = $row['name'];
      $price = $row['price'];
      $img_dir = $row['img_dir'];
    }
    else
    {
      $p_name = "삭제된 상품입니다.";
      $price = "";
      $img_dir = "";
    }

    $sql = 'SELECT * FROM seller WHERE s_id="'.$s_id.'"';
    $result = $conn->query($sql);

    //if ($result->num_rows == 1)
    //{
      $row = $result->fetch_assoc();

      $seller_name = $row['name'];
  		$seller_phone = $row['phone'];
  		$seller_bank = $row['bank'];
  		$seller_account = $row['account'];
    //}

    $sql = 'SELECT * FROM buyer WHERE b_id="'.$b_id.'"';
    $result = $conn->query($sql);

    //if ($result->num_rows == 1)
    //{
      $row = $result->fetch_assoc();

      $buyer_name = $row['name'];
      $buyer_phone = $row['phone'];
      $buyer_address = $row['address'];
    //}

	}
	else
	{
		echo '<script>alert("잘못된 접근입니다.");location.href="index.php";</script>';
		exit;
	}

	// Close connection
	$conn->close();
}
else
{
  echo '<script>alert("잘못된 접근입니다.");location.href="index.php";</script>';
  exit;
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
                <h2>주문서</h2>
	            </div>
	            <h3 class="fir">주문상세내역</h3>
	            <div class="table1">
                <table>
                  <thead>
                    <tr>
											<th>이미지</th>
											<th>상품명</th>
											<th>금액</th>
                      <th>주문현황</th>
                      <th>운송장번호</th>
                    </tr>
                  </thead>
                	<tbody>
                    <tr>
                      <td class="this-product">
												<img src="<?php echo $img_dir; ?>" width="40" class="middle" class="imgsize-s" />
                      </td>
											<td class="ta-c count this-product">
												<?php echo $p_name; ?>
											</td>
											<td rowspan="1" class="ta-c">
												<?php echo $total_price.'원'; ?>
											</td>
                      <td class="ta-c count this-product">
												<?php echo $status; ?>
											</td>
                      <td class="ta-c count this-product">
												<?php echo $delivery; ?>
											</td>
                    </tr>
									</tbody>
                </table>
							</div>
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
                            <div class="seller" >
															<?php echo $buyer_name; ?>
														</div>
													</td>
												</tr>
												<tr>
													<th class="ta-l">전화번호</th>
													<td>
                            <div class="seller" >
															<?php echo $buyer_phone; ?>
														</div>
													</td>
												</tr>
												<tr>
													<th class="ta-l">주소</th>
													<td>
                            <div class="seller" >
															<?php echo $buyer_address; ?>
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
