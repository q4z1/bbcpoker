<?php
// set cookie
if ($_POST['send'] != "" and $_POST['user'] != "") {
  $user = $_POST['user'];
  setcookie("user1", "$user", time()+30 * 86400, '/');
} //$_POST['send'] != "" and $_POST['user'] != ""
$amode = 0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc'] == 2)
    $amode = 2;
} //$_COOKIE['PHPSESSID'] != ""
if ($_GET['admin'] == 1 and $amode == 2)
  $amode = 1;
print '<!DOCTYPE html>
<html>';
ini_set('include_path', '/home/www/bbc/');
include "head.php";
$regulartaskcount = 1;
include "exp2/regulartasks.php";
print "<body style=\"position: relative;\">";
include "header1.php";
include "exp5/nav1.php";
// @XXX: debug div:
// echo '<div id="debug" style="display:none"></div>';
$url2 = "exp4/shoutbox2.php";
$url1 = "exp4/shoutbox1.php";
?>
<!-- @XXX: paging etc. - include jquery & featherlight: -->
<link href="/exp4/featherlight.min.css" type="text/css" rel="stylesheet" title="Featherlight Styles" />
<script type="text/javascript" src="/exp4/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="/exp4/featherlight.min.js"></script>
<script type="text/javascript" src="/exp4/encoder.js"></script>
<script src="/exp5/jquery.md5.js" type="text/javascript"></script>
<script type="text/javascript">
$(window).load(function(){$('#fp').val(fp());});

function fp()
{
    var sFP = "";
    sFP+="Resolution:"+window.screen.availWidth+"x"+window.screen.availHeight+"\n";
    sFP+="ColorDepth:"+screen.colorDepth+"\n";
    sFP+="UserAgent:"+navigator.userAgent+"\n";    
    sFP+="Timezone:"+(new Date()).getTimezoneOffset()+"\n";
    sFP+="Language:"+(navigator.language || navigator.userLanguage)+"\n";
    document.cookie="sFP";
    if (typeof navigator.cookieEnabled != "undefined" 
        && navigator.cookieEnabled == true
        && document.cookie.indexOf("sFP") != -1)
    sFP+="Cookies:true\n";
    else
    sFP+="Cookies:false\n";
    sFP+="Plugins:"+jQuery.map(navigator.plugins, function(oElement) 
		{ 
			return "\n"+oElement.name+"-"+oElement.version; 
		});
    return $.md5(sFP);
}
</script>
<!-- @xxx: paging etc. - JavaScript Code (depends on jquery!) -->
<script type='text/javascript'>
	
	// globals
	var refTimeout = 30000; // 30 Sekunden fÃ¼r Intervall
	var msgInterval = null;
	var page = 1;
	var showall = 0;
	
	var params =
	{
		page: page,
		sa: showall,
		type: "json",
		admin: <?=$amode?>
	};
	
	$( window ).load(function() {

		// initial shoutbox2b holen
		msg_refresh();
		
		// klick auf a#msg_refresh = manueller refresh
		$('#msg_refresh').click(function(){
			msg_refresh();
			return false;
		});
		
		// enter-taste im such feld:
		$('input#search').keyup(function(e) {
			if (e.which == 13)
			{
				if ($('#search').val() == "" || isNaN($('#search').val())) {
					$('#search').val('');
					return;
				}
				$.ajax({
					url: '<?= $url2 ?>',
					type: "GET",
					data: { search: $('#search').val(), admin: <?=$amode?>, t: $.now()},
					dataType: "html",
					// damit der Kram auch iso-8859-1 kodiert Ã¼bertragen wird:
					//contentType: 'Content-type: text/plain; charset=iso-8859-1',
					contentType: 'Content-type: text/plain; charset=utf8',
					// This is the imporant part!!!
					beforeSend: function(jqXHR)
					{
						//jqXHR.overrideMimeType('text/html;charset=iso-8859-1')
						jqXHR.overrideMimeType('text/html;charset=utf8')
					},
					complete: function( data )
					{
						$.featherlight(data.responseText);
					}
				});
			}
    });

		$('#doSearch').click(function(){
			if ($('#search').val() == "" || isNaN($('#search').val())) {
				$('#search').val('');
				return;
			}
			$.ajax({
				url: '<?= $url2 ?>',
				type: "GET",
				data: { search: $('#search').val(), admin: <?=$amode?>, t: $.now()},
				dataType: "html",
				// damit der Kram auch iso-8859-1 kodiert Ã¼bertragen wird:
				//contentType: 'Content-type: text/plain; charset=iso-8859-1',
				contentType: 'Content-type: text/plain; charset=utf8',
				// This is the imporant part!!!
				beforeSend: function(jqXHR)
				{
					//jqXHR.overrideMimeType('text/html;charset=iso-8859-1')
					jqXHR.overrideMimeType('text/html;charset=utf8')
				},
				complete: function( data )
				{
					$.featherlight(data.responseText);
				}
			});
		});

	});
	
	/* laden von shoutbox2b und einfÃ¼gen in den div#msg_box container */
	function msg_refresh()
	{
		if(msgInterval != null){
			clearInterval(msgInterval); // Timeout ggf. beenden
			msgInterval = null;
		}
		params =
		{
			page: page,
			sa: showall,
			type: "json",
			admin: <?=$amode?>,
			t: $.now(),
		};
		$.ajax({
			url: '<?= $url2 ?>',
			type: "GET",
			data: params,
			dataType: "json",
			// damit der Kram auch iso-8859-1 kodiert Ã¼bertragen wird:
			//contentType: 'Content-type: text/plain; charset=iso-8859-1',
			//contentType: 'Content-type: text/plain; charset=utf8',
			// This is the imporant part!!!
			beforeSend: function(jqXHR)
			{
        //jqXHR.overrideMimeType('text/html;charset=iso-8859-1')
				//jqXHR.overrideMimeType('text/html;charset=utf8')
			},
			complete: function( data )
			{
				fill_msg_box(data);
			}
		});
		msgInterval = setInterval(function(){msg_refresh()}, refTimeout); // neuer Interval fÃ¼r Aktualiserung
	}
	
	function fill_msg_box(json)
	{
		var obj = json.responseJSON;
		if (showall == 0) {
			$('#showall').html("<a href=\"#\" id=\"show_all\">Show All Messages</a>");
			$('#show_all').click(function(){
				showall = 1;
				msg_refresh();
				return false;
			});
			build_paging(obj.page, obj.max_page);
		}
		else
		{
			$('#paging').html("");
			$('#showall').html("<a href=\"#\" id=\"show_paging\">Show Pages</a>");
			$('#show_paging').click(function(){
				showall = 0;
				msg_refresh();
				return false;
			});
		}
		
		//$( '#msg_box' ).html( obj.html  );
		// @XXX: added utf-8 support with decoding any html-entities except for html-tags
		var decoded = Encoder.htmlDecode(obj.html);
		$( '#msg_box' ).html( decoded  );
		//$( '#msg_box' ).html( obj.html  );
	}
	
	function build_paging(actPage, max_page)
	{
		// unbind previos event-listeners if exists
		if ($('a.page').length > 0) {
			$('a.page').each( function( index, item){
				$(item).unbind("click");
			});
		}

		actPage = parseInt(actPage);
		max_page = parseInt(max_page);
		
		var pagination_links = "Page:&nbsp;&nbsp;";
		if (actPage > 1) {
			pagination_links += "<a href=\"#\" class=\"page\" _data_page_=\"1\">«</a>&nbsp;";
			pagination_links += "<a href=\"#\" class=\"page\" _data_page_=\"" + (actPage - 1) + "\">«</a>&nbsp;";
		} //$page > 1
		else {
			pagination_links += "«&nbsp;";
		}
		pagination_links += "&nbsp;" + actPage +"/" + max_page + "&nbsp;&nbsp;";
		if (actPage < max_page) {
			pagination_links += "<a href=\"#\" class=\"page\" _data_page_=\"" + (actPage + 1) + "\">»</a>&nbsp;";
			pagination_links += "<a href=\"#\" class=\"page\" _data_page_=\"" + max_page + "\">»</a>";
		} //$page < $max_page
		else {
			pagination_links += "»";
		}
		$('#paging').html(pagination_links);
		$('#paging').show();
		
		// new event-listeners:
		$('a.page').each( function( index, item){
			$(item).click(function(){
				page = $(this).attr('_data_page_');
				params =
				{
					page: page,
					sa: showall,
					type: "json",
					admin: <?=$amode?>,
					t: $.now(),  
				};
				msg_refresh();
				return false;
			});
		});
	}
</script>
<?php
if ($_POST['send'] != "") {
	
	if($_POST['fp'] == "" || !array_key_exists("fp", $_POST)){
		die("enable JavaScript!");
	}
	require_once("defines.php");
	if(in_array($_POST['fp'], $blocked)){
		die('IP blocked.');
	}
	
	
	
  $user = trim($_POST['user']);
  if ($user == "" or $user == " ")
    $user = "unnamed";
  $msg      = $_POST['gtfo'];
  $timetext = date("Y-m-d H:i:s");
  $user     = mysql_real_escape_string($user);
  $msg      = mysql_real_escape_string($msg);
  $error    = 0;
  $setting  = 2;
  if (strpos($user, "@") !== false)
    $error = 27938;
  if ($_POST['message'] != "")
    $error = 23483;
  if (trim($_POST['gtfo']) == "")
    $error = 23263;
  //TODO: add settings==4 if it contains badname or badword
  if ($amode == 1) {
    if ($user != $_SESSION['user3'])
      $error = 160;
    $setting = $_POST['aopt'];
    if ($setting != 1 and $setting != 3)
      $error = 160;
  } //$amode == 1
  $ttext1  = date("Y-m-d H:i:s", time() - 86400);
  $request = "SELECT COUNT(*) FROM shoutbox WHERE datetime>'$ttext1' AND setting='$setting' AND message='$msg'";
  $result  = mysql_query($request);
  $row     = mysql_fetch_array($result);
  
  // if($setting==2) $error=160; // NOTE HACK: quit rezos rage.
  $ipaddr=$_SERVER["REMOTE_ADDR"];
	$fp = $_POST['fp'];
  if ($row[0] != 0)
    $error = 160;
  $request = "INSERT INTO shoutbox
(name, datetime , message,setting,ip,fingerprint)
VALUES
(\"$user\",\"$timetext\",\"$msg\",$setting,\"$ipaddr\",\"$fp\")";
  if ($error == 0)
    $result = mysql_query($request) OR $error = 160;
  if ($error == 0)
    print "<h2>Your Message was Saved</h2>";
  else
    print "<h2>an error occured</h2>";
} //$_POST['send'] != ""
if ($amode == 1)
  print '<p><a href="exp4/shoutbox1.php">Switch to normal mode</a></p>';
if ($amode == 2)
  print '<p><a href="exp4/shoutbox1.php?admin=1">Switch to admin mode</a></p>';
$url1 = "exp4/shoutbox1.php";
if ($amode == 1)
  $url1 = "exp4/shoutbox1.php?admin=1";
print <<<E
<h1> Communication / Feedback </h1>

<hr />
<!--
<p>
<strong style="color: #990000">NEW BBC Settings are online! (2016-10-22)</strong></p>
<a href="http://bbcpoker.bplaced.net/exp4/description.php">New Settings</a>
<hr />
-->
E;

print <<<E
<h3> Make a New Message </h3>
<form action="$url1" method="post" >
<input type="hidden" name="fp" id="fp" />
E;

if ($user == "")
  $user = $_COOKIE['user1'];
$roption = "";
if ($amode == 1) {
  $user    = $_SESSION['user3'];
  $roption = 'readonly style="color:#ee1155"';
} //$amode == 1
print "Name: <input type=\"Text\" name=\"user\" value=\"$user\" maxlength=24 size=24 $roption><br>";
if ($amode == 1)
  print '<input type="radio" name="aopt" value=1 checked>Only for Admins<br>
<input type="radio" name="aopt" value=3>Message to all<br>';
if ($amode == 1)
  $url2 = "exp4/shoutbox2.php?admin=1";
if ($amode == 1)
  $url1 = "exp4/shoutbox1.php?admin=1";
print <<<E
<textarea name="message" rows=5 cols=50 maxlength=999 hidden></textarea><br>
<textarea name="gtfo" rows=5 cols=50 maxlength=999></textarea><br>
<input type="Submit" name="send" value="Send Message">
</form>
<hr>
<p><a href="#" id="msg_refresh">Refresh Messages</a> (will be done automatically every 30 seconds)</p>
<div style="margin: 5px auto; width: 80%;">
<div id="showall" style="text-align: left; float: left; width: 20%"></div>
<div id="paging" style="text-align: left; display: none; float: left;"></div>
<div id="search_field" style="text-align: left; float: right;">
	MSG-ID: <input type="text" name="search" id="search" value=""/>&nbsp;<input type="submit"
	name="doSearch" id="doSearch" value="Search"/>
</div>
<div style="clear: both;"></div>
</div>
<div id="msg_box" style="
	margin:0px auto; padding: 5px; border:1px solid black; width: 80%; height:480px;
	overflow-y: scroll; text-align: left"></div>
<div id="mylightbox"></div>
</div>
E;

include "footer1.php";
?>
</body>
</html>
