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

//$_GET['bno']이 있어야만 글삭제가 가능함.
if(isset($_GET['no']))
{
  $no = $_GET['no'];

	$no = mysqli_real_escape_string($conn, trim($no));

	$sql = 'SELECT * FROM notice WHERE numbers='.$no;
	$result = $conn->query($sql);

	// numbers가 기본키이므로 존재하면 1개
	if ($result->num_rows == 1)
	{
    $sql = 'DELETE FROM notice WHERE numbers='.$no;
    $result = $conn->query($sql);

    // 쿼리문이 제대로 수행되었다면
    if ($result)
    {
      echo '<script>alert("삭제되었습니다.");location.href="notice.php";</script>';
      exit;
    }
    else
    {
      echo '<script>alert("삭제 실패");location.href="notice.php";</script>';
      exit;
    }
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

?>
