<?php
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
<h1>Admin Manual</h1>
<div style="width: 70%; text-align: left; margin: 0px auto;">
<?php
session_start();
if(isset($_SESSION['upc']) && 0<(int)$_SESSION['upc'])
{
  $upc=(int)$_SESSION['upc'];
  $user3=$_SESSION['user3'];
}
else $upc=0;

//die(var_export($_SESSION, true));

if($upc === 0){
	die("For admins only.</div>");
}

?>

<pre>
1.GENERAL RULES

	1.1 general remarks:

	Description and Settings of BBC games you can find here:
	http://bbc.pokerth.net/exp4/description.php

	Only active admins are allowed to open BBC games, invite players and report a game:
	http://bbc.pokerth.net/exp4/admin1.php

	To check, whether S1 registered players also have an S2 ticket, go on:
	http://bbc.pokerth.net/exp1/reg3.php

	Co-Admins can help if asked by admin to do so.

	1.2 Usual Procedure:

		1.Open the game around 19:20 with bbcbot command or via stored settings. 
		(How to store them http://bbc.pokerth.net/exp1/StoreSettings.php  )
  
		2.Invite registered players from list.
  
		3.If players are missing at 19:30.59, invite substitutes(subs), then players of 
		 your choice from lobby.
		 additional: 
		-bbcbot commands will help: http://bbc.pokerth.net/exp3/bbcbotmanual.php 
		-admins may decide to wait a few minutes for a missing player 
		-players from lobby that aren't reg subs: BBC players with high rank should have 
		 priority
		-dont invite players with very bad stats or behavior
			
		If a player is missing at the very beginning of the game, please ask all players 
		to leave (make sure all left)the game and RESTART. 
  
		4.Only BBC games with 10 players are valid (exception: S4 games, which can start 
		  with 9 players if 10 were registered).
  
		5.For Co-Admins: Stay until the end of the game, post the url of the logfile in 
		  BBC chatbox with start time and step nr.
  
		6.If you have been disconnected, your logfile will be incomplete. 
		  In this case you have to ask other players (after reconnection) to deliver the 
		  loglink (best: finalist players)
		  or stay in lobby until the end of the game, and IMMEDIATELY demand them to 
		  provide the log link .
   
	  How to obtain a loglink:
	  http://bbc.pokerth.net/exp1/LogfileAnalysis.php
   
	  Example of a log link:
	  https://www.pokerth.net/log-file-analysis/?ID=6829c00ba...2889413a76ee9&UniqueGameID=1

	  info: logs will be deleted after 15 hrs from server.

2. UPLOADING(REPORTING) GAMES

	2.1 For standard upload:
		Use "Log-Link only upload (!)" http://bbc.pokerth.net/exp6/upload3.php
		Copy paste liglink url, select right game step and click on "upload files" button.
		
		On next page: 
		enter game start date and time (use only round times even if real start time may 
		differ a little),check whether players names and order are correct,
		if players were registered and didnt provide a valid (90 min prior) deregistration 
		in BBC chat:
		enter names of those missed players (only S2 upwards), and click on upload.
		On next page: get and copy BBcode 
	
		Open https://www.pokerth.net/community/bbcpoker
		and create new post in appropriate category by pasting the BBcode.
	
		Go back to BBC page, select "results" from menue, and check whether the game was 
		reported properly.
	
		Check "tickets changelog" whether the inputs are correct.
		
		
	2.2 Alternative upload:		
		
	  If a game consisted of less than 10 players, you have to input a game with
	  "Input without logfile" http://bbc.pokerth.net/exp2/test1.php
	  Copy paste nicks from logfile and add 0 as player10
	  Ignore the nxt error alert and go on.

    important: if an error occurs, and you dont understand why, please post an info with game nr, 
	       start time and error message. You should also check the results and look after the 
	       reported game.
	       if a game was reported with wrong time/step/missing players, ask sp0ck for 
	       correction in BBC chat		
		    
	info: With this link, you can get the BBcode of BBC games (X=step nr, Y=game nr)
	http://bbc.pokerth.net/exp6/getcode1.php?s=X&g=Y

3. TICKET RULES:
	
	3.1 Cases in which players lose ticket:
		
		If a player registers himself, do not appear* and provide no valid dereg.
		Applies to all:
			-players reg to T1 (in both cases: full set and not), regardlessly of 
			 anything else
			-reg sub players, which would be invited to T1 (in case T2 is not full set)
			-players reg to T2 (in case T2 is full set) 
		
		*appearance: 
		It is NOT sufficient only to be logged on 
		Playing a game, leaving lobby prior the start of a BBC game, no reaction to 
		invitation will be handled as "not appeared".
		Players have to be available for an admin.
			
	
		
	3.2 Cases in which players keep tickets: /Exceptions:
	
	   -if registered to BBC game and plays at MC tables
	   -if a player plays in BBC game which takes longer and overlaps with a later game
	   -if a player registered to a game, and in between used/lost his ticket (ticket count=0), 
	    so he cannot be invited
	   -if a player, reg for T1, was missing, and if he appears with delay to a T2 game of same 
	    step, if this table will start.
	   -if an admin (reg to f.e. T1) resigns and decides to open another (f.e. T2) table 
	    (info in lobby needed)
	   -if a game cannot be started due lack of admins 
		
4. BEHAVIORAL/NETIQUETTE:

	4.1 We expect from all admins kind behavior, relevance and objectivity in their posts and decisions.
	4.2 Personal aversions have to be deferred, and have to play no role in the decisions.
	4.3 Inputs made in DB/tickets by s-admins cannot be arbitrarily changed by other s-admins
		but only by the s-admin himself, by other s-admin on request or by BBC host.

5. Possible cases of non-compliance or violation of the rules "Admins Manual" have to be reported to
   BBC-host (sp0ck) and will be examined by him.

EDIT: 2018-11-26:

6. Poll votes and playing with multiple accounts from same IP/PC/Computer is not tolerated and might lead to deleting accounts, ticket removal or other appropriate actions.
	6.1. If you share Internet with another player, please inform about possible same IP registration/playing/voting in BBC Shoutbox.

7. If you are admin, please login and always post as admin in shoutbox and do not abuse shoutbox ("anonymously") for trolling and spamming.
	Admin state can be removed if shoutbox will be abused.
</pre>
</div>
<?php
include "footer1.php";
?>

</body>
</html>
