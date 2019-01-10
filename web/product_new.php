<?php

require_once("dbdata.php");

//echo "start";

$conn = mysqli_connect($servername,$username,$password_db, $dbname);

if (mysqli_connect_errno($conn))
{
  echo "실패";
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
  $s_id = $_SESSION['user_data'];
}
else
{
	$login = false;

  echo '<script>alert("로그인 후 시도해주세요.");location.href="login.php";</script>';
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


// Create connection
$conn = mysqli_connect($servername,$username,$password_db, $dbname);

// Check connection
if ($conn->connect_error)
{
	die("DB Connection failed: " . $conn->connect_error);
}

// html로 틀 꾸미고 input type 으로 받아서 get
?>

<form enctype="multipart/form-data" name="input" method="POST">
<div id="join-form">
 <div id="tit">
   <h3 align="center" >상품정보입력</h3>
 </div>
   <div class="section-body">
     <div class="table1" align="center">
       <table style="width:30% ;" >
         <colgroup>
           <col style="width:72px">
           <col>
           <col style="width:100px">
         </colgroup>
         <tbody style="float : left">
           <tr>
             <th scope="col" class="no">상품명	</th>
             <th>
               <input id="name" class="txtfield" name="newname" type="text"/>
             </th>
           </tr>
           <tr>
             <th scope="col" class="no">분류	</th>
             <th>
               <select name="newcategory1" >
                 <option>분류없음</option>
                 <option>kimch</option>
               </select><br/>
             </th>
           </tr>
           <tr>
             <th scope="col" class="no">판매가	</th>
             <th>
               <input id="price" class="txtfield" name="newprice" type="text"  />
             </th>
           </tr>
           <tr>
             <th scope="col" class="no">단위</th>
             <th>
               <input id="measure" class="txtfield" name="newmeasure" type="text"/>
             </th>
            </tr>
            <tr>
              <th scope="col" class="no">상품설명	</th>
              <th>
                <input id="content" class="txtfield" name="newcontent"  type="text"/>
              </th>
            </tr>
            <tr>
              <th scope="col" class="no">사진	</th>
              <th>
               <input type="file" name="userfile" >
               <input type="hidden" name="filedir" />
              </th>
            </tr>
           </tbody>
          </table>
         </div>
         <div class="btn" align="center">
           <input type="submit" style="width: 100px;height: 30px;" value=" 상품등록 " />
           <button type="button" class="cancel"><a href="./index.php">취소</a></button>
         </div>
       </div>
     </div>
   </form>
   <div id="footer">
     <img src="./img_main/footer.png" style="width:100%">
   </div>
<?php

// post로 요청받은 데이터가 있을 경우
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

  $name = $_POST['newname'];
  $category1 = $_POST['newcategory1'];
  $price = $_POST['newprice'];
  $measure = $_POST['newmeasure'];
  $start_time = date('Y-m-d');
  $content = $_POST['newcontent'];


  $uploaddir = 'C:\xampp\htdocs\project\img_goods\\';
  $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

  echo '<pre>';
  if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
      // echo "파일이 유효하고, 성공적으로 업로드 되었습니다.\n";
  } else {
      // print "파일 업로드 공격의 가능성이 있습니다!\n";
  }

  // echo '자세한 디버깅 정보입니다:';
  // print_r($_FILES);

  print "</pre>";
  $img_dir = $_FILES['userfile']['name'];

  if(empty($name)||empty($category1)||empty($price)||empty($measure)||empty($img_dir)){
    echo '<script>alert("빈칸을 모두 채워주세요.");history.back(-1);</script>';
  }
  else
	{   //유효성 가격 : 숫자인지 확인
    if(!is_numeric($price)){
      echo '<script>alert("가격은 숫자만 써주세요.");history.back(-1);</script>';
    }// 문자열 변수에 숫자이외의 문자가 포함되어 있으면
    else{
      $sql = 'INSERT INTO product (p_id, s_id, name, category1, category2, price, measure, start_time, content, img_dir)
            values(null, "'.$s_id.'", "'. $name.'", "'.$category1.'",null,"'. $price.'","'. $measure.'","'. $start_time.'"
                    ,"'. $content.'","'. $img_dir.'")';

		  $result = $conn->query($sql);

		// query가 정상실행 되었다면,
		  if($result)
		  {
			// 자동 카운트된 숫자를 $no로 저장
			$no = $conn->insert_id;
			// 작성된 게시물로 바로 가려면 이동할 URL 설정 ( 아님)
			//$replaceURL = './product_list.php';

			echo '<script>alert("정상적으로 글이 등록되었습니다.");';

			//header('Location: product_list.php');
		  }
		  else
		  {
			echo '<script>alert("등록에 실패했습니다.");history.back(-1);</script>';
			exit;
		  }

		// Close connection
		  $conn->close();
	   }
  }
}


?>


  <div id="bottom">
		<img src="./img_main/bottom.png" alt="">
	</div>
	<div id="footer">
		<img src="./img_main/footer.png" alt="">
	</div>
</div>
</body>
</html>
