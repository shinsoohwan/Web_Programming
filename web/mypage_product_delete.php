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

if ($type == 'seller')
{
  for ($i=1; $i<=15; $i++)
	{
		if (isset($_GET['pid'.$i]))
		{
			$p_id = $_GET['pid'.$i];

			$p_id = mysqli_real_escape_string($conn, trim($p_id));

      $sql = "DELETE FROM product WHERE p_id='".$p_id."'";
      $result = $conn->query($sql);

      if ($result)
      {
        // 삭제 성공
      }
      else
      {
        // 삭제 오류
        echo '<script>alert("삭제 실패.");location.href="mypage_produce_delete.php";</script>';
        exit;
      }
    }
	}

	// 선택한거 모두 삭제 성공
  echo '<script>alert("성공적으로 삭제되었습니다.");location.href="mypage_product.php";</script>';
  exit;
}
else
{
	echo '<script>alert("잘못된 접근입니다.");location.href="index.php";</script>';
	exit;
}
?>
