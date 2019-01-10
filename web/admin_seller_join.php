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

	echo '<script>alert("잘못된 접근입니다.");location.href="index.php";</script>';
	exit;
}

if ($type == 'admin')
{
	if (isset($_POST['submit']))
	{
	  $new_id = $_POST['userid'];
	  $password = $_POST['password'];
	  $password_check = $_POST['password_check'];
	  $user_name = $_POST['username'];
	  $user_phone = $_POST['userphone'];
		$user_bank = $_POST['userbank'];
		$user_account = $_POST['useraccount'];

	  if (empty($new_id) || empty($password) || empty($password_check) || empty($user_name)
	      || empty($user_phone) || empty($user_bank) || empty($user_account))
	  {
	    echo '<script>alert("빈칸을 채우시오.");history.back(-1);</script>';
			exit;
	  }
	  else if ($password != $password_check)
	  {
	    echo '<script>alert("비밀번호를 정확히 입력하시오.");history.back(-1);</script>';
			exit;
	  }
	  // 여기에서 validation 검사
	  else
	  {
	    // ID 유효성 검사
	    if (!preg_match('/^[A-Za-z][A-Za-z0-9]{4,20}$/', $new_id))
	    {
	      echo '<script>alert("ID는 첫글자는 영문자이며 대소문자, 숫자만 사용할 수 있습니다. (4~20자리)");history.back(-1);</script>';
	      exit;
	    }

	    // 비밀번호 유효성 검사
	    if (!preg_match('/^[A-Za-z0-9]{6,16}$/', $password))
	    {
	      echo '<script>alert("비밀번호는 대소문자, 숫자만 사용할 수 있습니다. (6~16자리)");history.back(-1);</script>';
	      exit;
	    }

	    // 이름 유효성 검사
	    if (!preg_match('/\p{Hangul}/u', $user_name))
	    {
	      echo '<script>alert("이름은 한글만 사용할 수 있습니다.");history.back(-1);</script>';
	      exit;
	    }
	    if ((strlen($user_name) < 3) || (strlen($user_name) > 15))
	    {
	      echo '<script>alert("이름은 한글 1~5자리입니다.");history.back(-1);</script>';
	      exit;
	    }

	    // 전화번호 유효성 검사
	    $user_phone = str_replace("-", "", $user_phone);
	    if (!preg_match('/^[0-9]{9,12}$/', $user_phone))
	    {
	      echo '<script>alert("전화번호를 제대로 입력하십시오.");history.back(-1);</script>';
	      exit;
	    }

	    // 은행 유효성 검사
			// 계좌번호 유효성 검사

	    // 모든 유효성 검사 통과 후
	    // DB에 회원정보 등록
			$new_id = mysqli_real_escape_string($conn, trim($new_id));

			$sql = 'SELECT id FROM member WHERE id="'.$new_id.'"';
	    $result = $conn->query($sql);

	    if ($result->num_rows != 0)
	    {
	      echo '<script>alert("중복된 ID 입니다.");history.back(-1);</script>';
	      exit;
	    }

	    // 비밀번호 암호화
	    $pw = sha1($password);

			$user_name = mysqli_real_escape_string($conn, trim($user_name));
			$user_phone = mysqli_real_escape_string($conn, trim($user_phone));
			$user_bank = mysqli_real_escape_string($conn, trim($user_bank));
			$user_account = mysqli_real_escape_string($conn, trim($user_account));

	    // 최종적으로 DB에 회원정보 추가
	    $sql = "INSERT INTO member values('".$new_id."','".$pw."','seller')";
	    $result = $conn->query($sql);

			if ($result->num_rows == 0)
			{
				echo '<script>alert("판매자 등록에 실패했습니다.");location.href="admin_seller.php";</script>';
				exit;
			}

	    $sql = "INSERT INTO seller values('".$new_id."','".$user_name."','".$user_phone."','".$user_bank."','".$user_account."')";
	    $result = $conn->query($sql);

			if ($result->num_rows == 0)
			{
				echo '<script>alert("판매자 등록에 실패했습니다.");location.href="admin_seller.php";</script>';
				exit;
			}

	    // Close connection
	    $conn->close();

	    // 회원가입 완료 페이지로 이동
			echo '<script>alert("판매자 등록이 완료되었습니다.");location.href="admin_seller.php";</script>';
			exit;
		}

		$password = "";
	  $password_check = "";
	}
}
else
{
  echo '<script>alert("권한이 없습니다.");location.href="index.php";</script>';
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
  <link type="text/css" rel="stylesheet" href="./join.css">
  <link type="text/css" rel="stylesheet" href="./mp_update.css">

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
				<li><a href="./admin.php">관리자페이지</a></li>
			</ul>
		</div>
		<div id="top_menu1">
			<ul>
				<li><a href="./logout.php">로그아웃</a></li>
				<li><a href="./admin.php">관리자페이지</a></li>
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

    <form id="joinForm" name="joinForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" >
      <div id="join">
        <div class="top">
          <h2>관리자페이지</h2>
					<button type="button" class="mp-menu"><a href="admin_order.php">주문 관리</a></button>
          <button type="button" class="mp-menu"><a href="admin_product.php">상품 관리</a></button>
          <button type="button" class="mp-menu"><a href="admin_seller_join.php">판매자 등록</a></button>
          <button type="button" class="mp-menu"><a href="admin_seller.php">판매자 관리</a></button>
					<button type="button" class="mp-menu"><a href="admin_buyer.php">구매자 관리</a></button>
        </div>
				<div id="join-form">
    			<div id="tit">
    				<h3>판매자 등록</h3>
    			</div>
          <div id="form_inner">
            <div>
    					<table border="0" summary="">
    						<caption></caption>
    						<colgroup>
    							<col style="width:163px;"/>
    							<col style="width:auto;"/>
    						</colgroup>
    						<tbody>
    							<tr>
    								<th scope="row">ID</th>
    								<td>
											<input id="name" class="txtfield" name="userid" value="<?php echo $new_id; ?>" type="text"  />
    								</td>
    							</tr>
									<tr>
										<th scope="row">비밀번호</th>
										<td>
											<input id="passwd" class="txtfield" name="password" placeholder="영문/숫자/특수문자 중 2가지 이상 조합, 10자~16자"value="" type="password"  />
										</td>
									</tr>
									<tr>
										<th scope="row">비밀번호 확인</th>
										<td>
											<input id="passwd_confirm" class="txtfield" name="password_check" value="" type="password"  /> <span id="pwConfirmMsg"></span>
										</td>
									</tr>
    							<tr>
    								<th scope="row">이름</th>
    								<td>
    									<input id="passwd_confirm" class="txtfield" name="username" value="<?php echo $user_name; ?>" type="text"  /> <span id="pwConfirmMsg"></span>
    								</td>
    							</tr>
    							<tr>
    								<th scope="row" id="nameTitle">전화번호</th>
    								<td>
    									<input id="name" class="txtfield" name="userphone" value="<?php echo $user_phone; ?>" type="text"  />
    								</td>
    							</tr>
            			<tr>
            				<th scope="row">입금은행</th>
            				<td><input id="address" class="txtfield" type="text" name="userbank" value="<?php echo $user_bank; ?>"></td>
            			</tr>
            			<tr>
            				<th scope="row">계좌번호</th>
            				<td><input id="address" class="txtfield" type="text" name="useraccount" value="<?php echo $user_account; ?>"></td>
            			</tr>
    						</tbody>
    					</table>
    				</div>
            <br/><br/>
            <div class="btn">
              <input type="submit" class="gaip" name="submit" value="등록">
              <button type="button" class="cancel"><a href="./index.php">취소</a></button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

	<div id="footer">
		<img src="./img_main/footer.png" alt="">
	</div>
</body>
</html>
