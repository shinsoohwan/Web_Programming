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

/* 페이징 시작 */
//페이지 get 변수가 있다면 받아오고, 없다면 1페이지를 보여준다.
if(isset($_GET['page']))
{
	$page = $_GET['page'];
}
else
{
	$page = 1;
}

/* 검색 시작 */
if(isset($_GET['searchColumn']))
{
	$searchColumn = $_GET['searchColumn'];
	$subString .= '&amp;searchColumn=' . $searchColumn;
}
if(isset($_GET['searchText']))
{
	$searchText = $_GET['searchText'];
	$subString .= '&amp;searchText=' . $searchText;
}

if(isset($searchColumn) && isset($searchText))
{
	$searchSql = ' where ' . $searchColumn . ' like "%' . $searchText . '%"';
}
else
{
	$searchSql = '';
}
/* 검색 끝 */

//$sql = 'SELECT count(*) as cnt FROM notice ORDER BY numbers DESC';
$sql = 'SELECT count(*) as cnt FROM notice'.$searchSql;
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$allPost = $row['cnt']; //전체 게시글의 수

if(empty($allPost))
{
	$emptyData = '<tr><td colspan="5">글이 존재하지 않습니다.</td></tr>';
}
else
{
  $onePage = 15; // 한 페이지에 보여줄 게시글의 수.
  $allPage = ceil($allPost / $onePage); //전체 페이지의 수

  // 0번 페이지거나 최대 페이지를 넘어갔을 경우
	if ($page < 1 || ($allPage && $page > $allPage))
	{
		echo '<script>alert("존재하지않는 페이지입니다.");location.href="index.php";</script>';
		exit;
	}

  $oneSection = 10; //한번에 보여줄 총 페이지 개수(1 ~ 10, 11 ~ 20 ...)
  $currentSection = ceil($page / $oneSection); //현재 섹션
  $allSection = ceil($allPage / $oneSection); //전체 섹션의 수

  $firstPage = ($currentSection * $oneSection) - ($oneSection - 1); //현재 섹션의 처음 페이지

  if($currentSection == $allSection)
  {
  	$lastPage = $allPage; //현재 섹션이 마지막 섹션이라면 $allPage가 마지막 페이지가 된다.
  }
  else
  {
  	$lastPage = $currentSection * $oneSection; //현재 섹션의 마지막 페이지
  }

  $prevPage = (($currentSection - 1) * $oneSection); //이전 페이지, 11~20일 때 이전을 누르면 10 페이지로 이동.
  $nextPage = (($currentSection + 1) * $oneSection) - ($oneSection - 1); //다음 페이지, 11~20일 때 다음을 누르면 21 페이지로 이동.

  $paging = '<ul class="pagination">'; // 페이징을 저장할 변수

  //첫 페이지가 아니라면 처음 버튼을 생성
  if($page != 1)
  {
    $paging .= '<li class="active"><a href="notice.php?page=1' . $subString . '">처음</a></li>';
  }
  //첫 섹션이 아니라면 이전 버튼을 생성
  if($currentSection != 1)
  {
    $paging .= '<li class="active"><a href="notice.php?page=' . $prevPage . $subString . '">이전</a></li>';
  }

  for($i = $firstPage; $i <= $lastPage; $i++)
  {
  	if($i == $page)
    {
  		$paging .= '<li class="active">' . $i . '</li>';
  	}
    else
    {
  		$paging .= '<li class="active"><a href="notice.php?page=' . $i . $subString . '">' . $i . '</a></li>';
  	}
  }

  //마지막 섹션이 아니라면 다음 버튼을 생성
  if($currentSection != $allSection)
  {
    $paging .= '<li class="active"><a href="notice.php?page=' . $nextPage . $subString . '">다음</a></li>';
  }

  //마지막 페이지가 아니라면 끝 버튼을 생성
  if($page != $allPage)
  {
    $paging .= '<li class="active"><a href="notice.php?page=' . $allPage . $subString . '">끝</a></li>';
  }
  $paging .= '</ul>';

  /* 페이징 끝 */
  $currentLimit = ($onePage * $page) - $onePage; //몇 번째의 글부터 가져오는지
  $sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; //limit sql 구문

   //원하는 개수만큼 가져온다. (0번째부터 20번째까지
  //$sql = 'SELECT * FROM notice ORDER BY numbers DESC'.$sqlLimit;
  $sql = 'SELECT * FROM notice '.$searchSql.' ORDER BY numbers DESC '.$sqlLimit;
  $result = $conn->query($sql);
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
							<?php
							if ($type == 'admin')
							{
							?>
							<a class="write" href="notice_write.php"><em>글쓰기</em></a>
							<?php
							}
							?>
				    </div>
				    <div class="section-body">
			        <div class="table1" align="center">
		            <table style="width:100%" >
					<colgroup>
					<col style="width:72px">
                    <col>
                    <col style="width:100px">
	                </colgroup>
	                <thead>
		                <tr>
	                    <th scope="col" class="no">번호</th>
	                    <th scope="col" class="title">제목</th>
	                    <th scope="col" class="date">날짜</th>
		                </tr>
	                </thead>
	                <tbody>
										<?php
											if(isset($emptyData))
											{
												echo $emptyData;
											}
											else
											{
												// 모든 게시물 출력
												while ($row = $result->fetch_assoc())
												{
													$no = $row['numbers'];
													$title = $row['title'];
													$date = $row['write_date'];
										?>
										<tr style="height:10px">
											<td class="no"><?php echo $no; ?></td>
											<td class="title">
												<a href="notice_view.php?no=<?php echo $no; ?>">
													<?php echo $title; ?>
												</a>
											</td>
											<td class="date"><?php echo $date; ?></td>
										</tr>
										<?php
												}
											}
										?>
	                </tbody>
		            </table>
			        </div>

							<div class="paging">
								<?php echo $paging; ?>
							</div>
					    <div class="searchBox">
								<form action="notice.php" method="get">
									<select name="searchColumn">
										<option <?php echo $searchColumn=='title'?'selected="selected"':null?> value="title">제목</option>
										<option <?php echo $searchColumn=='content'?'selected="selected"':null?> value="content">내용</option>
									</select>
									<input type="text" name="searchText" value="<?php echo isset($searchText)?$searchText:null?>">
									<button type="submit">검색</button>
								</form>
							</div>
				    </div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="footer">
		<img src="./img_main/footer.png" style="width:100%">
	</div>
</body>
</html>
