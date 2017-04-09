<?php
$auth1=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc']==2 or $_SESSION['upc']==3)
    $auth1 = 1;
} //$_COOKIE['PHPSESSID'] != ""

print '<!DOCTYPE html>
<html>';
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
include "head.php";
$regulartaskcount=1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php"; 
include "exp5/nav1.php";
?>


<div style="margin-left:auto;margin-right:auto;width:70%">

<p>Hi guys!</p>

<p>Thank you very much for this lovely &quot;Hello World Cup&quot;
It was very nice to see you guys again, it was really great, there are lots of lovely people in BBC :) -- and i even managed to reach the final.
</p>

<p>Thanks for all the admins of BBC for organizing games from day to day.
However, i'd like to put some emphasis on two of them:
</p>
<ul>
<li><b>sp0ck</b>, for running bbcbot and helping with programming. I know i can trust you with giving you the password for the website;) </li>
<li><b>Nelly</b>, the superadmin, who was in BBC since game 1. It was really cool to build the website and administrating everything together with you.
I think your BBC leadership played an important role for the well-doing of BBC.</li>
</ul>

<p>
I did the programming for the BBC website, because it was fun for me and it gave me an opportunity to exercise my amateur programming skills.
Also i am always full of ideas what could be programmed/improved for BBC/pokerth, but only had the time to realise a small part of these ideas.
However, a while ago i decided that i should save some time for other priorities in my life and therefor leaving the BBC community,
so i guess i should kind of apologize for that. But i am also proud of the website, it was the first programming project that i did that was useful
for more than 3 people. When i started doing stuff for BBC more than two years ago, i never thought that it would be so successful and big.
</p>

<p>
If you ask what i do now: still a bit of programming, but not as much as earlier. The last programm that i wrote monitors (and limits) my computer time :D
 - right now i am about 4 hours daily, which leaves me more time for sleeping :D. Also i am still very young, the time i spent with BBC roughly represents
 a tenth of my total live time, and i am really busy with university right now.
</p>

<p>
If you want anything from me, just email me. And maybe, one day, i will come back to BBC ;)
</p>

</div>
<?php
include "footer1.php";
?>

</body>
</html>
