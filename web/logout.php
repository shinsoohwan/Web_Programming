<?php

// 쿠키값 비워준다.
setcookie("user_data", "");

// 세션을 비우고 모든 세션 변수값을 삭제한다.
session_start();
$_SESSION = array();
session_destroy();

// 로그아웃 후 돌아갈 페이지 설정
// 로그인 페이지로 할지 메인화면으로 할지 정해야함
// 별도의 로그아웃 화면을 띄우는건 요즘 잘 하지 않고
// 로그아웃 되었다는 팝업을 띄우기도 하지만 대부분 생략
header("Location: login.php");

?>
