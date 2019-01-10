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

	if ($type != 'admin')
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

//$_GET['no']이 있을 때만 $no 선언
if(isset($_GET['no']))
{
	$no = $_GET['no'];

	$no = mysqli_real_escape_string($conn, trim($no));

	$sql = 'SELECT * FROM notice WHERE numbers='.$no;
	$result = $conn->query($sql);

	// numbers가 기본키이므로 존재하면 1개
	if ($result->num_rows == 1)
	{
		$row = $result->fetch_assoc();

		$title = $row['title'];
		$content = $row['content'];
	}
	else
	{
		echo '<script>alert("존재하지 않는 게시물입니다.");location.href="notice.php";</script>';
		exit;
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$title = $_POST['title'];
		$content = $_POST['content'];

		if (empty($title) && !empty($content))
		{
			echo '<script>alert("제목을 입력하시오.");history.back(-1);</script>';
			exit;
		}
		else if (!empty($title) && empty($content))
		{
			echo '<script>alert("내용을 입력하시오.");history.back(-1);</script>';
			exit;
		}
		else if (empty($title) && empty($content))
		{
			echo '<script>alert("제목과 내용을 입력하시오.");history.back(-1);</script>';
			exit;
		}
		// 제목과 내용을 입력했을 경우
		else
		{
			$title = mysqli_real_escape_string($conn, trim($title));
			$content = mysqli_real_escape_string($conn, trim($content));

			$sql = 'UPDATE notice SET title="'.$title.'", content="'.$content.'" WHERE numbers='.$no;

			$result = $conn->query($sql);

			// query가 정상실행 되었다면,
			if($result)
			{
				// 작성된 게시물로 바로 가려면 이동할 URL 설정
				$replaceURL = './notice_view.php?no='.$no;

				echo '<script>alert("정상적으로 글이 수정되었습니다.");location.href="notice.php";</script>';
				//header('Location: '.$replaceURL);
				exit;
			}
			else
			{
				echo '<script>alert("수정 실패했습니다.");history.back(-1);</script>';
				exit;
			}
		}
	}

	// Close connection
	$conn->close();
}
else
{
	echo '<script>alert("잘못된 접근입니다.");location.href="notice.php";</script>';
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
	<link type="text/css" rel="stylesheet" href="./notice_write.css">
	<link type="text/css" rel="stylesheet" href="./main.css">
	<link type="text/css" rel="stylesheet" href="./media.css">
	<link type="text/css" rel="stylesheet" href="./notice_view.css">

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
	 	<div id="container">
      <div id="content">
				<div class="contents-inner cs-page">
					<div class="section">
				    <div class="section-header">
							<h2 class="h2">공지사항</h2>
    				</div>
    				<div class="section-body">
							<div class="join-form">
	            	<form name="frmWrite" id="frmWrite" action="<?php echo $_SERVER['PHP_SELF']; ?>?no=<?php echo $no; ?>" method="post" enctype="multipart/form-data" class="frmWrite">
	                <input type="hidden" name="bdId" value="goodsreview">
	                <input type="hidden" name="sno" value="">
	                <input type="hidden" name="mode" value="write">
	                <!--<input type="hidden" name="chkSpamKey" id="chkSpamKey">-->
	                <input type="hidden" name="returnUrl" value="bdId=goodsreview">
									<div class="table1 board-write">
										<table>
											<colgroup>
												<col style="width:133px;">
												<col>
											</colgroup>
											<tbody>
												<tr>
													<th class="ta-l">제목</th>
													<td>
														<div class="txt-field">
															<input type="text" name="title" class="text" value="<?php echo $title; ?>">
														</div>
													</td>
												</tr>
												<tr>
													<th class="ta-l">본문</th>
													<td>
														<div class="txtarea">
															<textarea cols="30" name="content" rows="10" class="w100" id="editor"><?php echo $content; ?></textarea>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="btn">
										<input type="submit" class="save" value="수정">
										<button type="button" class="cancel"><a href="notice.php">이전</a></button>
									</div>
		            </form>
							</div>
						</div>
					</div>
					<div id="footer">
						<img src="./img_main/footer.png" alt="">
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
