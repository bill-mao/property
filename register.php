<?php require_once('Connections/conn.php'); ?>
<?php
///=/if(isset($_SERVER)){
	//$test = (string)rawurldecode($_SERVER['QUERY_STRING']);
//	echo "<script type='text/javascript'>alert($test);</script>";}



if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="register.php?这个账号已经被注册过了'";
  $loginUsername = $_POST['ID'];
  $LoginRS__query = sprintf("SELECT ID FROM householder WHERE ID=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_conn, $conn);
  $LoginRS=mysql_query($LoginRS__query, $conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO householder (ID, name, phone, pwd, sex, birth, question, answer, IDNum) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['pwd'], "text"),
                       GetSQLValueString($_POST['RadioGroup1'], "text"),
                       GetSQLValueString($_POST['user_date'], "date"),
                       GetSQLValueString($_POST['question'], "text"),
                       GetSQLValueString($_POST['answer'], "text"),
                       GetSQLValueString($_POST['ID_num'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_conn, $conn);
$query_register = "SELECT * FROM householder";
$register = mysql_query($query_register, $conn) or die(mysql_error());
$row_register = mysql_fetch_assoc($register);
$totalRows_register = mysql_num_rows($register);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>register</title>
<script type="text/javascript" src="JS/menu.JS">
if()
</script>
</head>

<body>

<div style="background-image:url(Images/register.jpg)" align="center">
  <tr>
    <td height="30"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="15" height="30"><img src="images/tab_03.gif" width="15" height="30" /></td>
          <td background="images/tab_05.gif"><img src="images/311.gif" width="16" height="16" /> <span class="STYLE4">欢迎注册**物业信息管理平台</span></td>
          <td width="14"><img src="images/tab_07.gif" width="14" height="30" /></td>
        </tr>
      </table></td>
  </tr>

  
  <form name="form1" action="<?php echo $editFormAction; ?>" method="POST" id="form1" target="_self">
    <table width="419" border="1" cellspacing="2" cellpadding="2" summary="摘要">
      <caption>
      请阅读提示信息，认真填写，谢谢~
      </caption>
      <tr>
        <th width="17" scope="row">登录账号</th>
        <td width="382"><input type="text" placeholder="可以是中文" name="ID" id="ID" /></td>
      </tr>
      <tr>
        <th scope="row">密码</th>
        <td><input type="text" name="pwd" id="pwd" /></td>
      </tr>
      <tr>
        <th scope="row">密码验证</th>
        <td><input type="text" name="pwd2" id="pwd2" /></td>
      </tr>
      <tr>
        <th scope="row">身份证号</th>
        <td><input type="text" name="ID_num" id="ID_num" /></td>
      </tr>
      <tr>
        <th scope="row">真实姓名</th>
        <td><input type="text" name ="name" id="realname" /></td>
      </tr>
      <tr>
        <th scope="row">手机号</th>
        <td><input type="text" name="phone" id="phone" /></td>
      </tr>
      <tr>
        <th scope="row">性别</th>
        <td><table width="200">
            <tr>
              <td nowrap="nowrap"><label>
                  <input name="RadioGroup1" type="radio" id="RadioGroup1_0" value="男" checked="checked" />
                  男</label></td>
              <td nowrap="nowrap">&nbsp;
                <label>
                  <input type="radio" name="RadioGroup1" value="女" id="RadioGroup1_1" />
                  女</label></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <th scope="row">出生年月</th>
        <td><input id="date" type="date" name="user_date" /></td>
      </tr>
      <tr>
        <th scope="row">密码找回问题</th>
        <td><textarea name="question" id="question" cols="45" rows="5"></textarea></td>
      </tr>
      <tr>
        <th scope="row">找回答案</th>
        <td><input type="text" name="answer" id="answer" /></td>
      </tr>
      <tr>
        <th colspan="2" scope="row"><input name="submit" type="submit" value="注册" />
          &nbsp;</th>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1" />
  </form>
  
  
  <tr>
    <td height="29"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="15" height="29"><img src="images/tab_20.gif" width="15" height="29" /></td>
          <td background="images/tab_21.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> </tr>
            </table></td>
          <td width="14"><img src="images/tab_22.gif" width="14" height="29" /></td>
        </tr>
      </table></td>
  </tr>

</div>
</html>
<?php
mysql_free_result($register);
?>
