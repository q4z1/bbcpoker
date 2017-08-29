<?php
if($error==0 ) goto end;
print "<p><span style=\"font-size:120%\"> An Error Occured: </span>";
switch($error){
case 1: 
	print "<b>the password is wrong</b> or you did not enter any data. if you forgot the password, ask someone.";
	break;
case 2: 
	print "Every Place needs a name. if less than 10 players played, use 0 as input. ";
	break;
case 3: 
case 41:
case 220:
	print "Step has to be a value from 1 to 4. ";
	break;
case 4: 
	print "No Double usernames please ";
	break;
case 5:
	print "put the name 0 at the end.";
	break; 
case 6:
	print "New players are only in step 1 allowed";
	break; 
case 7:
	print "go to <a href=\"http://bbcpoker.bplaced.net/exp2/test1.php\">input page</a> and enter some data!";
	break; 
//case 8:
case 9:
	print "The result of the game was already put in";
	break;
case 14:
	print "There is a new player, which has not been marked as new player. maybe it was an typing error. see info for playername.";
	break;
case 17:
case 18:
case 19:
case 20:
case 21:
case 22:
case 23:
	print "Database Error";
	break;
case 8:
	print "You should have at least 2 real players. ";
	break;
case 7:
	print "there is no error description yet";
	break;
	
case 46:
case 47:
	print "A player who was reserved but not missing does not have a ticket to step 2";break;
case 48:
	print "A player who was reserved but not missing does not have a ticket to step 3 or 4";break;
case 49:
	print "A player does not have a ticket to step 2";break;
case 50:
	print "A player does not have a ticket to step 3 or step 4";break;
case 51:
	print "date and time are in the future or have the wrong format";
	break;
case 52:
	print "You cant take tickets away in step 1 - leave it empty";
	break;
case 53:
case 54:
	print "A player entered in \"reserved but missing\" could not be found";break;
case 55:
case 56:
	print "There are identical players somewhere";break;
case 57:
case 58:
case 59:
case 60:
case 61:
case 62:
case 63:
case 64:
case 65:
case 66:
	print "there is an error with date/time or tickets";break;


case 121:
	print "password is wrong or you didnt enter any data at all...";break;
case 122:
case 123:
	print "player could not be found";break;
case 124:
case 125:
	print "problem with manipulate tickets";break;
case 201:
case 202:
case 203:
case 204:
case 205:
case 206:
case 207:
case 208:
case 209:
case 210:
case 211:
case 212:
case 213:
case 214:
case 215:
	print "there is an error in the database";break;
//300 - 399 is for registration
case 300:
	print "an error with registration occured";break;
case 301:
	print "your playername could not be found. If you  play BBC for the first time, 
	<a href=\"exp5/reg1.php\">GO BACK</a> and mark yourself as new player";break;
case 302:
	print "you said you are new to BBC, but we found your name in our databse, 
	<a href=\"exp5/reg1.php\">GO BACK</a> and correct that";break;
case 303:
	print "you registered for step 2 without a ticket to step 2,
	<a href=\"exp5/reg1.php\">GO BACK</a> or <a href=\"exp4/tickets2.php\">See ticket table</a>";break;
case 304:
	print "you registered for step 3 without a ticket to step 3,
	<a href=\"exp5/reg1.php\">GO BACK</a> or <a href=\"exp4/tickets2.php\">See ticket table</a>";break;
case 305:
	print "Your input data has wrong format.";break;
case 306:
	print "you registered for step 4 without a ticket to step 4,
	<a href=\"exp5/reg1.php\">GO BACK</a> or <a href=\"exp4/tickets2.php\">See ticket table</a>";break;
case 307:
case 308:
	print "There is a problem with the time of the game. <a href=\"exp5/reg1.php\">Please try again</a>.";break;
case 309:
	print "there is already a registration for this name";break;
case 310:
	print "we did not received data from you. <a href=\"exp5/reg1.php\">Please try again</a>.";break;
case 312:
	print "there is an error with your name. we are sorry.";break;
case 311:
case 313:
case 314:
	print 'there is an internal error. we are sorry. <a href="exp5/reg3.php">Please check if you registration succeeded</a>';break;
case 315:
	print 'Please <a href="exp5/reg1.php">GO BACK</a> and select the step you want to play (e.g. step 1).';break;
	
	
case 330:
	print 'we did not receive data from you';break;
case 331:
	print 'we did not receive data from you';break;
case 332:
	print "There is a problem with the time of the game.";break;
case 333:
	print "There is an error with deregistration.";break;
case 334:
	print "We could not find your name";break;
case 335:
	print "Your registration code is invalid!!.";break;
case 336:
	print "We could not find a registration.";break;
case 337: 
	print "It is too late for deregistration";break;
	
case 399:
	print 'another admin is using an input page, please try again in 5 minutes';break;
	
	
case 400:
case 401:
	print 'there is an error with file uploading (first file)';break;
case 402:
	print 'there is an error with file uploading (second file)';break;
case 403:
	print 'there is an error with file uploading (third file)';break;
case 404:
	print 'the filesize of the first file is not ok';break;
case 405:
	print 'the filesize of the second file is not ok';break;
case 406:
	print 'the filesize of the third file is not ok';break;
case 407:
	print 'the first file has to be a *.html file';break;
case 408:
	print 'the second file should be "hand_cash.png"';break;
case 409:
	print 'the third file should be "pot_size.png"';break;
case 410:
	print 'we dont know what game did you choose';break;
case 411:
	print 'you have the wrong password or did not enter any';break;
case 412:
	print 'the server couldnt store your .html file';break;
case 413:
	print 'Your html file looks strange, maybe click on "expand all" before saving';break;
case 414:
	print 'the server couldnt store your hand_cash.png file';break;
case 415:
	print 'the server couldnt store your pot_size.png file';break;
case 416:
	print 'an error with reading the logfile occured';break;
case 417:
	print 'an error with reading the logfile occured';break;
case 418:
	print 'the game number is blocked';break;
case 419:
	print 'an error with reading the logfile occured';break;
case 420:
	print 'an error with reading the logfile occured';break;
case 430:
	print 'we could not find exactly 10 players';break;
case 431:
	print 'an empty playername was found';break;
case 432:
	print 'we think the logfile is broken (the name of a player looks weird). please contact an experienced admin';break;
case 501:
	print 'the programmer made a super noob mistake';break;
case 502:
	print 'the programmer made a super noob mistake';break;
case 503:
	print 'the programmer made a super noob mistake';break;
case 504:
	print 'the programmer made a super noob mistake';break;
case 601:
	print 'we could not find your name';break;
case 602:
	print 'there was an internal mistake';break;
case 603:
	print 'something is wrong with your name';break;
case 604:
	print 'it looks like you already voted';break;
case 605:
	print 'blocked poll';break;
case 606:
	print 'the programmer made a super noob mistake';break;
case 607:
	print 'the programmer made a super noob mistake';break;
case 608:
	print 'the programmer made a super noob mistake';break;
case 900:
    print 'log-link is not valid!'; break;
	
	
default: 
	print "we dont have further information about the error. ";
	break;
}
print "(Error code: $error;info: $errorinfo1 )</p>";
end:
print " ";
?>
