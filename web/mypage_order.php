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

/* 페이징 시작 */
//페이지 get 변수가 있다면 받아오고, 없다면 1페이지를 보여준다.
if (isset($_GET['page']))
{
	$page = $_GET['page'];
}
else
{
	$page = 1;
}

if ($type == 'buyer')
{
	$sql = "SELECT count(*) as cnt FROM orders WHERE b_id='".$user_id."' ORDER BY o_id DESC";
}
else if ($type == 'seller')
{
	$sql = "SELECT count(*) as cnt FROM orders WHERE s_id='".$user_id."' ORDER BY o_id DESC";
}
else if ($type == 'admin')
{
	header('Location: admin_order.php');
	exit;
}

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$allPost = $row['cnt']; //전체 게시글의 수

$onePage = 15; // 한 페이지에 보여줄 게시글의 수.

$allPage = ceil($allPost / $onePage); //전체 페이지의 수

if ($page < 1 || ($allPage && $page > $allPage))
{
	echo '<script>alert("존재하지않는 페이지입니다.");location.href="index.php";</script>';
	exit;
}

$oneSection = 10; //한번에 보여줄 총 페이지 개수(1 ~ 10, 11 ~ 20 ...)

$currentSection = ceil($page / $oneSection); //현재 섹션
$allSection = ceil($allPage / $oneSection); //전체 섹션의 수
$firstPage = ($currentSection * $oneSection) - ($oneSection - 1); //현재 섹션의 처음 페이지

if ($currentSection == $allSection)
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
if ($page != 1)
{
	$paging .= '<li class="active"><a href="mypage_order.php?page=1">처음</a></li>';
}

//첫 섹션이 아니라면 이전 버튼을 생성
if ($currentSection != 1)
{
	$paging .= '<li class="active"><a href="mypage_order.php?page=' . $prevPage . '">이전</a></li>';
}

for ($i = $firstPage; $i <= $lastPage; $i++)
{
	if ($i == $page)
	{
		$paging .= '<li class="active">' . $i . '</li>';
	}
	else
	{
		$paging .= '<li class="active"><a href="mypage_order.php?page=' . $i . '">' . $i . '</a></li>';
	}
}

//마지막 섹션이 아니라면 다음 버튼을 생성
if ($currentSection != $allSection)
{
	$paging .= '<li class="active"><a href="mypage_order.php?page=' . $nextPage . '">다음</a></li>';
}

//마지막 페이지가 아니라면 끝 버튼을 생성
if ($page != $allPage)
{
	$paging .= '<li class="active"><a href="mypage_order.php?page=' . $allPage . '">끝</a></li>';
}

$paging .= '</ul>';

/* 페이징 끝 */

$currentLimit = ($onePage * $page) - $onePage; //몇 번째의 글부터 가져오는지
$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; //limit sql 구문

//원하는 개수만큼 가져온다. (0번째부터 20번째까지
if ($type == 'buyer')
{
	$sql = "SELECT * FROM orders WHERE b_id='".$user_id."' ORDER BY o_id DESC".$sqlLimit;
}
else if ($type == 'seller')
{
	$sql = "SELECT * FROM orders WHERE s_id='".$user_id."' ORDER BY o_id DESC".$sqlLimit;
}

$result = $conn->query($sql);

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

    <form id="joinForm" name="joinForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" >
      <div id="join">
        <div class="top">
          <h2>마이페이지</h2>
					<?php
					if ($type == 'seller')
					{
					?>
          <button type="button" class="mp-menu"><a href="mypage_product.php">등록상품조회</a></button>
					<?php
					}
					?>
          <button type="button" class="mp-menu"><a href="mypage_order.php">주문배송조회</a></button>
          <button type="button" class="mp-menu"><a href="mypage_personal.php">개인정보수정</a></button>
        </div>
        <div id="join-form">
    			<div id="tit">
    				<h3>주문배송조회</h3>
    			</div>
    			<div id="form_inner">
    				<div>
    					<table border="0" summary="">
    						<caption></caption>
    						<colgroup>
    							<col style="width:100px;"/>
    							<col style="width:auto;"/>
    						</colgroup>
    						<tbody>
    							<tr>
                    <th scope="row">주문번호</th>
                    <th scope="row">상품명</th>
                    <th scope="row">주문일</th>
                    <th scope="row">주문현황</th>
										<th scope="row">운송장번호</th>
    							</tr>
                  <?php
              		while ($row = $result->fetch_assoc())
              		{
              			$o_id = $row['o_id'];
              			$p_id = $row['p_id'];
              			$date = $row['order_time'];
              			$status = $row['status'];
										$delivery = $row['delivery'];

              			$sql = "SELECT * FROM product WHERE p_id='".$p_id."'";
              			$result2 = $conn->query($sql);
              			$row2 = $result2->fetch_assoc();

              			$title = $row2['name'];
              		?>
              		<tr>
              			<td><?php echo $o_id; ?></td>
              			<td><a href="order_data.php?oid=<?php echo $o_id; ?>"><?php echo $title; ?></a></td>
              			<td><?php echo $date; ?></td>
              			<td><?php echo $status; ?></td>
										<td><?php echo $delivery; ?></td>
              		</tr>
              		<?php
              		}
              		?>
    						</tbody>
    					</table>
							<div class="paging">
								<?php echo $paging ?>
							</div>
    				</div>
    				<br/><br/>
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
