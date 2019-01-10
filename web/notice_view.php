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
}

//$_GET['no']이 있을 때만 $no 선언
if(isset($_GET['no']))
{
	$no = $_GET['no'];

  if (!empty($no))
  {
		$no = mysqli_real_escape_string($conn, trim($no));

    $sql = 'SELECT title, content, write_date FROM notice where numbers = '.$no;
    $result = $conn->query($sql);

    // numbers가 기본키이므로 존재하면 1개
    if ($result->num_rows == 1)
    {
      $row = $result->fetch_assoc();

      $title = $row['title'];
      $content = $row['content'];
      $date = $row['write_date'];
    }
    else
    {
      echo '<script>alert("존재하지 않는 게시물입니다.");location.href="notice.php";</script>';
			exit;
    }

    // Close connection
    $conn->close();
  }
  else
  {
    echo '<script>alert("잘못된 접근입니다.");location.href="notice.php";</script>';
		exit;
  }
}
// get 방식으로 전달받지 못했다면
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
	 	<div id="container">
      <div id="content">
				<div class="contents-inner cs-page">
					<div class="section">
				    <div class="section-header">
							<h2 class="h2">공지사항</h2>
    				</div>
    				<div class="section-body">
        			<div class="board-view">
            		<div class="board-view-head">
                	<div class="board-view-title">
                    <h2>
                    	<?php echo $title; ?>
                    </h2>
                	</div>
                	<div class="board-view-info">
                    <div class="author">
											<span class="text1"><strong>관리자</strong>
											</span>
											<span class="divide-bar">&nbsp;</span>
											<span class="text2"><?php echo $date; ?></span>
										</div>
									</div>
            			<div class="board-view-body">
		                <div class="textfield">
											<?php echo $content; ?>
		                </div>
			            </div>
								</div>
								<br/>
								<hr/>
								<div class="board-ctrl-btn">
									<?php
									if ($type == 'admin')
									{
									?>
									<a class="boardview-list" href="notice_update.php?no=<?php echo $no; ?>">
										<em>수정</em>
									</a>
					      	<a class="boardview-list" href="notice_delete.php?no=<?php echo $no; ?>">
										<em>삭제</em>
									</a>
									<?php
									}
									?>
									<a class="boardview-list" href="notice.php"><em>목록</em></a>
								</div>
								<br/>
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
