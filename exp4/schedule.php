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

<h1>Schedule</h1>
<table border="1" style="width: 50%">
    <tr>
        <th colspan="4">Official schedule</th>    
    </tr>
    <tr>
        <td rowspan="3">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr style="font-weight: bold;">
        <td>19:30 CEST</td>
        <td>21:30 CEST</td>
        <td>23:15 CEST</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Monday</td>
        <td>Step1</td>
        <td>Step1</td>
        <td>Step1</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Tuesday</td>
        <td>Step1</td>
        <td>Step1</td>
        <td>Step1</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Wednesday</td>
        <td>Step1</td>
        <td>Step2</td>
        <td>Step2</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Thursday</td>
        <td>Step1</td>
        <td>Step1</td>
        <td>Step1</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Friday</td>
        <td>Step1<br />Step3<br />Step4</td>
        <td>Step2</td>
        <td>Step1</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Saturday</td>
        <td>Step1</td>
        <td>Step1</td>
        <td>Step2</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Sunday</td>
        <td>Step2</td>
        <td>Step1</td>
        <td>Step1</td>
    </tr>
</table>
<!-- NEW END--> 

<?php
include "footer1.php";
?>

</body>
</html>