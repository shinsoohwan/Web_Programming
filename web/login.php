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
	header('Location: index.php');
}

// post로 요청받은 데이터가 있을 경우
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$pre_url = $_POST['pre_url'];
	$user = $_POST['id'];
	$password = $_POST['password'];

	// 로그인 페이지가 처음 열렸다면
	if (empty($user) && empty($password) && empty($pre_url))
	{
		// 아무 일도 하지않고 로그인 페이지 띄움
		//echo "empty";
	}
	// 로그인 페이지가 다른 페이지를 거쳐왔거나 처음 열린게 아니면
	else
	{
		// 만약 첫접속->로그인실패의 경우라면 이전페이지를 메인으로 설정
		if (empty($pre_url))
		{
			$pre_url = "index.php";
		}
		//echo $pre_url;

		// ID와 비밀번호를 입력하지 않았을 경우
		if (empty($user) && empty($password))
		{
			echo '<script>alert("ID와 비밀번호를 입력하시오.");history.back(-1);</script>';
			exit;
		}
		// ID는 비우고 비밀번호만 입력했을 경우
		else if (empty($user) && !empty($password))
		{
			echo '<script>alert("ID를 입력하시오.");history.back(-1);</script>';
			exit;
		}
		// ID는 입력했지만 비밀번호를 입력하지 않았을 경우
		else if (!empty($user) && empty($password))
		{
			echo '<script>alert("비밀번호를 입력하시오.");history.back(-1);</script>';
			exit;
		}
		else
		{
			// Create connection
			$conn = new mysqli($servername, $username, $password_db, $dbname);

			// Check connection
			if ($conn->connect_error)
			{
				die("DB Connection failed: " . $conn->connect_error);
			}

			$user = mysqli_real_escape_string($conn, trim($user));

			$sql = 'SELECT password FROM member where id="'.$user.'"';
			$result = $conn->query($sql);

			// ID가 일치하는 튜플이 존재하면
			// ID가 PK이므로 중복 튜플은 없다.
			if ($result->num_rows == 1)
			{
				$row = $result->fetch_assoc();
				$pw_db = $row["password"];
			}
			// ID가 일치하는 튜플이 없다면
			else
			{
				echo '<script>alert("일치하는 ID가 없습니다.");history.back(-1);</script>';
				exit;
			}

			// Close connection
			$conn->close();

			// 비밀번호를 암호화해서 DB에 저장 및 비교
			$pw = sha1($password);

			// 비밀번호가 일치하면
			if ($pw == $pw_db)
			{
				// 쿠키에 저장
				// 쿠키는 클라이언트에 저장되는 데이터
				// 파라미터는 쿠키명, 값, 지속시간(or 만기시간. 단위는 초. 30일 만기), 경로 (경로는 생략)
				setcookie("user_data", $user, time() + (60 * 60 * 24 * 30));

				//세션 생성
				session_start();

				// 세션변수에 로그인한 유저명 저장
				$_SESSION['user_data'] = $user;

				header("Location: ".$pre_url);
			}
			// 비밀번호가 일치하지 않으면
			else
			{
				// 경고창 출력 후 원상태로
				echo '<script>alert("비밀번호를 잘못 입력하셨습니다.");history.back(-1);</script>';
				exit;
			}
		}
	}
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
		<link type="text/css" rel="stylesheet" href="./login.css">

		<title>8조</title>
	</head>

<body>
	<div id="frame">
	<div id="top_menu">
		<span class="slogan">
			<img src="./img_main/slogan.png">
		</span>
		<ul>
			<li><a href="./login.php">로그인</a></li>
			<li><a href="./join.php">회원가입</a></li>
			<li><a href="./index.php">마이페이지</a></li>
		</ul>
	</div>

<div id="top_menu1">

		<ul>
			<li><a href="./login.php">로그인</a></li>
			<li><a href="./join.php">회원가입</a></li>
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

	<div id="login">
		<div id="bigbox">
<h1></h1>
<div id="box">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="login">
                <div class="input-info">
                    <div>
                        <input type="text" class="text" id="loginId" name="id" value="<?php echo $user; ?>" placeholder="아이디" required="true">
                    </div>
                    <div>
                        <input type="password" class="text" id="loginPwd" name="password" value="" placeholder="비밀번호" required="true">
                    </div>
                </div>
                <button type="submit" class="skinbtn point2 l-login"><em>로그인</em></button>
						</div>

            <div class="btn">
			<p>
			<em>아직 회원이 아니신가요?</em>
			</p>
			<button type="button" class="skinbtn base3 l-join" id="btnJoinMember"><em><a href="./join.php"> 회원가입</a></em></button>

			</div>

	</form>
	</div>
</div>

	</div>
	<div id="footer">
		<img src="./img_main/footer.png" alt="">
	</div>
	</div>
</body>
</html>
