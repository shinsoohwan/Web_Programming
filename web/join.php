<?php

require_once("dbdata.php");

session_start();

if (isset($_COOKIE['user_data']) || isset($_SESSION['user_data']))
{
  echo '<script>alert("잘못된 접근입니다..");location.href="index.php";</script>';
  exit;
}

if (isset($_POST['submit']))
{
  $id = $_POST['id'];
  $password = $_POST['password'];
  $password_check = $_POST['password_check'];
  $name = $_POST['name'];
  $address = $_POST['address'];
  $phone = $_POST['phone1'].$_POST['phone2'].$_POST['phone3'];

  if(isset($_POST["captcha"]) && $_POST["captcha"] == "")
  {
    echo '<script>alert("빈칸을 채우시오.");history.back(-1);</script>';
    exit;
  }
  else if (isset($_POST["captcha"]) && $_POST["captcha"] != "" && $_SESSION["code"] != $_POST["captcha"])
  {
    echo '<script>alert("자동회원가입방지 암호를 제대로 입력해주세요.");history.back(-1);</script>';
    exit;
  }
  else if (isset($_POST["captcha"]) && $_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"])
  {
    // 제대로 입력함
  }
  else
  {
    echo '<script>alert("잘못된 접근입니다..");location.href="index.php";</script>';
    exit;
  }

  if (empty($id) || empty($password) || empty($password_check) || empty($name)
      || empty($phone) || empty($address))
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
    if (!preg_match('/^[A-Za-z][A-Za-z0-9]{3,19}$/', $id))
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
    if ((strlen($name) < 2) || (strlen($name) > 15))
    {
      echo '<script>alert("이름은 2~15자리입니다.");history.back(-1);</script>';
      exit;
    }

    // 전화번호 유효성 검사
    //$phone = str_replace("-", "", $phone);
    if (!preg_match('/^[0-9]{9,12}$/', $phone))
    {
      echo '<script>alert("전화번호를 제대로 입력하십시오.");history.back(-1);</script>';
      exit;
    }

    // 주소 유효성 검사
    // 주소는 유효성 검사하기 힘들어서 패스

    // 모든 유효성 검사 통과 후
    // DB에 회원정보 등록

    // Create connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error)
    {
      die("DB Connection failed: " . $conn->connect_error);
    }

    $id = mysqli_real_escape_string($conn, trim($id));

    $sql = 'SELECT id FROM member WHERE id="'.$id.'"';
    $result = $conn->query($sql);

    if ($result->num_rows != 0)
    {
      echo '<script>alert("중복된 ID 입니다.");history.back(-1);</script>';
      exit;
    }

    // 비밀번호 암호화
    $pw = sha1($password);

    $name = mysqli_real_escape_string($conn, trim($name));
    $phone = mysqli_real_escape_string($conn, trim($phone));
    $address = mysqli_real_escape_string($conn, trim($address));

    // 최종적으로 DB에 회원정보 추가
    $sql = "INSERT INTO member values('".$id."','".$pw."','buyer')";
    $result = $conn->query($sql);

    if (!$result)
    {
      echo '<script>alert("회원가입에 실패했습니다.");location.href="index.php";</script>';
      exit;
    }

    $sql = "INSERT INTO buyer values('".$id."','".$name."','".$phone."','".$address."')";
    $result = $conn->query($sql);

    if (!$result)
    {
      echo '<script>alert("회원가입에 실패했습니다.");location.href="index.php";</script>';
      exit;
    }

    // Close connection
    $conn->close();

    // 회원가입 완료 페이지로 이동
    echo '<script>alert("회원가입이 완료되었습니다.");location.href="login.php";</script>';
    exit;
  }

  $password = "";
  $password_check = "";
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

	<div id="join">
        <div class="top">
            <h2>회원가입</h2>
        </div>
        <div id="join-form">
		<form id="joinForm" name="joinForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" >
			<div id="tit">
				<h3>기본정보</h3>
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
								<th scope="row">아이디</th>
								<td>
									<input id="member_id" class="txtfield" name="id" placeholder="영문 소문자/숫자, 4~16자" value="<?php echo $id; ?>" type="text"  /> <span id="idMsg"></span>
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
								<th scope="row" id="nameTitle">이름</th>
								<td>
									<input id="name" class="txtfield" name="name" value="<?php echo $name; ?>" type="text"  />
								</td>
							</tr>
							<tr class="">
								<th scope="row">주소</th>
								<td>
									<input id="address" class="txtfield" name="address" placeholder="" value="<?php echo $address; ?>" type="text"  />
								</td>
							</tr>
							<tr class="">
								<th scope="row">전화번호</th>
								<td>
									<select id="phone1" name="phone1" >
										<option value="02">02</option>
										<option value="031">031</option>
										<option value="032">032</option>
										<option value="033">033</option>
										<option value="041">041</option>
										<option value="042">042</option>
										<option value="043">043</option>
										<option value="044">044</option>
										<option value="051">051</option>
										<option value="052">052</option>
										<option value="053">053</option>
										<option value="054">054</option>
										<option value="055">055</option>
										<option value="061">061</option>
										<option value="062">062</option>
										<option value="063">063</option>
										<option value="064">064</option>
										<option value="0502">0502</option>
										<option value="0503">0503</option>
										<option value="0504">0504</option>
										<option value="0505">0505</option>
										<option value="0506">0506</option>
										<option value="0507">0507</option>
										<option value="070">070</option>
										<option value="010">010</option>
										<option value="011">011</option>
										<option value="016">016</option>
										<option value="017">017</option>
										<option value="018">018</option>
										<option value="019">019</option>
									</select>-
									<input id="phone2" name="phone2" maxlength="4" value="" type="text"  />-
									<input id="phone3" name="phone3" maxlength="4" value="" type="text"  />
								</td>
							</tr>
              <tr class="">
								<th scope="row">자동회원가입방지</th>
								<td>
									<input id="address" class="txtfield" name="captcha" type="text" maxlength="4"/>
                  <img src="captcha.php" />
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<br/><br/>
				<div class="btn">
					<input type="submit" class="gaip" name="submit" value="회원가입">
					<button type="button" class="cancel"><a href="./index.php">취소</a></button>
				</div>
			</div>
        </form>
        </div>
	</div>
	<div id="footer">
		<img src="./img_main/footer.png" alt="">
	</div>

</body>
</html>
