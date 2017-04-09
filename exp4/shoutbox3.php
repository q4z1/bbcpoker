<?php
// set cookie
$amode = 0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc'] == 2)
    $amode = 1;
} //$_COOKIE['PHPSESSID'] != ""
// amode is in [0,1], should be 1 to view page
print '<!DOCTYPE html>
<html>';
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
include "head.php";
$regulartaskcount = 1;
include "exp2/regulartasks.php";
include "exp4/sbfun.php";
print "<body style=\"position: relative;\">";
include "header1.php";
include "exp5/nav1.php";
// @XXX: debug div:
// echo '<div id="debug" style="display:none"></div>';
$url2 = "exp4/shoutbox2.php";
$url1 = "exp4/shoutbox3.php";
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
    return $.md5(sFP);
}
</script>
<!-- @xxx: paging etc. - JavaScript Code (depends on jquery!) -->

<script type='text/javascript'>
	
	// globals
	var refTimeout = 60000; // 60 Sekunden für Intervall
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
					// damit der Kram auch iso-8859-1 kodiert übertragen wird:
					contentType: 'Content-type: text/plain; charset=iso-8859-1',
					// This is the imporant part!!!
					beforeSend: function(jqXHR)
					{
						jqXHR.overrideMimeType('text/html;charset=iso-8859-1')
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
				// damit der Kram auch iso-8859-1 kodiert übertragen wird:
				contentType: 'Content-type: text/plain; charset=iso-8859-1',
				// This is the imporant part!!!
				beforeSend: function(jqXHR)
				{
					jqXHR.overrideMimeType('text/html;charset=iso-8859-1')
				},
				complete: function( data )
				{
					$.featherlight(data.responseText);
				}
			});
		});

	});
	
	/* laden von shoutbox2b und einfügen in den div#msg_box container */
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
            deleted: 1,
			t: $.now(),
		};
		$.ajax({
			url: '<?= $url2 ?>',
			type: "GET",
			data: params,
			dataType: "json",
			// damit der Kram auch iso-8859-1 kodiert übertragen wird:
			contentType: 'Content-type: text/plain; charset=iso-8859-1',
			// This is the imporant part!!!
			beforeSend: function(jqXHR)
			{
        jqXHR.overrideMimeType('text/html;charset=iso-8859-1')
			},
			complete: function( data )
			{
				fill_msg_box(data);
			}
		});
		msgInterval = setInterval(function(){msg_refresh()}, refTimeout); // neuer Interval für Aktualiserung
	}
	
	function fill_msg_box(json)
	{
<?php
  if(1 != $amode) // not logged in
  {
   echo <<<E
    $( '#msg_box').html("You are not logged in or are not allowed to view this");
    return false;
E;

  }
?>
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
			pagination_links += "<a href=\"#\" class=\"page\" _data_page_=\"" + (actPage - 1) + "\"></a>&nbsp;";
		} //$page > 1
		else {
			pagination_links += "«&nbsp;";
		}
		pagination_links += "&nbsp;" + actPage +"/" + max_page + "&nbsp;&nbsp;";
		if (actPage < max_page) {
			pagination_links += "<a href=\"#\" class=\"page\" _data_page_=\"" + (actPage + 1) + "\"></a>&nbsp;";
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
// if ($amode == 1)
//   print '<p><a href="exp4/shoutbox1.php">Switch to normal mode</a></p>';
// if ($amode == 2)
//   print '<p><a href="exp4/shoutbox1.php?admin=1">Switch to admin mode</a></p>';
$url1 = "exp4/shoutbox1.php";
if ($amode == 1)
  $url1 = "exp4/shoutbox1.php?admin=1";
if ( $amode == 1)
{
print <<<E
<h1> Feedback / Alternative</h1>
<hr />
E;

}

print <<<E
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
if(1==$amode)
{
    print deletionguidelines();
}
include "footer1.php";
?>
</body>
</html>
