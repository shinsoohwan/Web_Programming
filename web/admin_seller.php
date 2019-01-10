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

	$sql = "SELECT count(*) as cnt FROM seller ORDER BY name";
	//$sql = "SELECT * FROM seller";

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
		$paging .= '<li class="active"><a href="admin_seller.php?page=1">처음</a></li>';
	}

	//첫 섹션이 아니라면 이전 버튼을 생성
	if ($currentSection != 1)
	{
		$paging .= '<li class="active"><a href="admin_seller.php?page=' . $prevPage . '">이전</a></li>';
	}

	for ($i = $firstPage; $i <= $lastPage; $i++)
	{
		if ($i == $page)
		{
			$paging .= '<li class="active">' . $i . '</li>';
		}
		else
		{
			$paging .= '<li class="active"><a href="admin_seller.php?page=' . $i . '">' . $i . '</a></li>';
		}
	}

	//마지막 섹션이 아니라면 다음 버튼을 생성
	if ($currentSection != $allSection)
	{
		$paging .= '<li class="active"><a href="admin_seller.php?page=' . $nextPage . '">다음</a></li>';
	}

	//마지막 페이지가 아니라면 끝 버튼을 생성
	if ($page != $allPage)
	{
		$paging .= '<li class="active"><a href="admin_seller.php?page=' . $allPage . '">끝</a></li>';
	}

	$paging .= '</ul>';

	/* 페이징 끝 */

	$currentLimit = ($onePage * $page) - $onePage; //몇 번째의 글부터 가져오는지
	$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; //limit sql 구문

	//원하는 개수만큼 가져온다. (0번째부터 20번째까지
	$sql = "SELECT * FROM seller ORDER BY name".$sqlLimit;

	$result = $conn->query($sql);
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

    <form id="joinForm" name="joinForm" action="admin_seller_delete.php" method="get" enctype="multipart/form-data" >
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
    				<h3>판매자 관리</h3>
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
                    <th scope="row">선택</th>
                    <th scope="row">ID</th>
                    <th scope="row">이름</th>
                    <th scope="row">전화번호</th>
                    <th scope="row">은행명</th>
                    <th scope="row">계좌번호</th>
    							</tr>
                  <?php
            			$i = 1;
            			while ($row = $result->fetch_assoc())
            			{
            				$id = $row['s_id'];
            				$name = $row['name'];
            				$phone = $row['phone'];
            				$bank = $row['bank'];
										$account = $row['account'];
            			?>
            			<tr>
            				<td><input type="checkbox" name="sid<?php echo $i; ?>" value="<?php echo $id; ?>"></td>
            				<td><?php echo $id; ?></td>
										<td><?php echo $name; ?></td>
										<td><?php echo $phone; ?></td>
										<td><?php echo $bank; ?></td>
										<td><?php echo $account; ?></td>
            			</tr>
            			<?php
            				$i = $i + 1;
            			}
            			?>
    						</tbody>
    					</table>
							<div class="paging">
								<?php echo $paging ?>
							</div>
    				</div>
    				<br/>
    				<div class="btn">
    					<input type="submit" class="gaip" value="삭제">
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
