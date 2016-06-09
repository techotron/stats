<html>
<head>
<title>Stats Site</title>
<link type="text/css" rel="stylesheet" href="styles.css"/>
</head>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1"><META http-equiv="refresh" content="5">
<body>

<?php
$myServer = "SQL SERVER NAME";
$myUser = "DBUSER";
$myPass = "DBPASS";
$myDB = "DATABASE";

//echo "Before connecting to the database<br>";

//Connection to the database
$dbhandle = mssql_connect($myServer, $myUser, $myPass)
	or die("Couldn't connect to SQL Server on $myServer<br>");

//echo "Connection to the database<br>";

//Select a database to work with
$selected = mssql_select_db($myDB, $dbhandle)
	or die("Couldn't open database $myDB");

//Date section
$today = date("Y-m-d 23:59:59.999");
$now = date("Y-m-d H:i:s");
$nextWeek = date("Y-m-d H:i:s", strtotime($today. ' + 5 days'));

//This is needed to satisfy the date format that the table displays so that different CSS formatting is used
$ammendedToday = date("M j Y H:i:s:000A");

echo "Current time is: " . $now . "<br>";
//echo "Today variable is: " . $today . "<br>";

//SQL Statement for tickets due today
$ticketsDueToday = "SELECT ROW_NUMBER() OVER (ORDER BY DueDate) AS rowID, dbo.HD_Ticket.TicketID, dbo.HD_Ticket.DueDate, dbo.HD_Contact.FullName FROM dbo.HD_Ticket LEFT JOIN dbo.HD_Contact ON dbo.HD_Contact.ContactId = dbo.HD_Ticket.Assignee WHERE StatusId != 5 AND DueDate < '" . $today ."' ORDER BY DueDate";


//SQL Statement for tickets due in 5 days
$ticketsDueNextWeek = "SELECT ROW_NUMBER() OVER (ORDER BY DueDate) AS rowID, dbo.HD_Ticket.TicketID, dbo.HD_Ticket.DueDate, dbo.HD_Contact.FullName FROM dbo.HD_Ticket LEFT JOIN dbo.HD_Contact ON dbo.HD_Contact.ContactId = dbo.HD_Ticket.Assignee WHERE StatusId != 5 AND DueDate < '" . $nextWeek ."' ORDER BY DueDate";


//SQL Statmeent for total number of open tickets
$totalTickets = "SELECT dbo.HD_Ticket.TicketID FROM dbo.HD_Ticket WHERE StatusId != 5";


//Display Total number of tickets
$resultTotalTickets = mssql_query($totalTickets);
$numRowsTotal = mssql_num_rows($resultTotalTickets);
echo "<standardPageHeader1>" . $numRowsTotal . " Open Ticket" . ($numRowsTotal == 1 ? "" : "s") . " in Total</standardPageHeader1><br>";

/*

//Display Tickets due today in table
$resultDueToday = mssql_query($ticketsDueToday);
$numRows = mssql_num_rows($resultDueToday);
echo "<h1>" . $numRows . " Ticket" . ($numRows == 1 ? "" : "s") . " Due Today (or earlier)</h1>";

echo "<table border='1' cellpadding='3' cellspacing='1'>";
echo "<tr>";
//echo "<th> Row ID </th>";
echo "<th class=tableHeader> Ticket ID </th>";
echo "<th class=tableHeader> Due Date </th>";
echo "<th class=tableHeader> Assignee </th>";
echo "</tr>";

//Loop through rows and format each depending on factors
while($row1 = mssql_fetch_array($resultDueToday))
{

	if (strtotime($row1['DueDate']) <= strtotime($now)) {

                echo "<tr>";
                echo "<td class=pastDueDate>" . $row1['TicketID'] . "</td>";
                echo "<td class=pastDueDate>" . $row1['DueDate'] . "</td>";
                echo "<td class=pastDueDate>" . $row1['FullName'] . "</td>";
                echo "</tr>";

	} elseif ($row1['rowID'] % 2 == 0) {

		echo "<tr>";
		echo "<td class=evenTableLine>" . $row1['TicketID'] . "</td>";
		echo "<td class=evenTableLine>" . $row1['DueDate'] . "</td>";
		echo "<td class=evenTableLine>" . $row1['FullName'] . "</td>";
		echo "</tr>";
	
	} else {

                echo "<tr>";
                echo "<td class=oddTableLine>" . $row1['TicketID'] . "</td>";
                echo "<td class=oddTableLine>" . $row1['DueDate'] . "</td>";
                echo "<td class=oddTableLine>" . $row1['FullName'] . "</td>";
                echo "</tr>";

	}
}

echo "</table><br>";

*/

//Display tickets due next week in table
$resultDueNextWeek = mssql_query($ticketsDueNextWeek);
$numRowsNextWeek = mssql_num_rows($resultDueNextWeek);
echo "<standardPageHeader1>" . $numRowsNextWeek . " Ticket" . ($numRowsNextWeek == 1 ? "" : "s") . " Due Next Week (or earlier)</standardPageHeader1>";

echo "<table class=mainTable cellpadding='3' cellspacing='1'>";
echo "<tr>";
echo "<th class=tableHeader> Ticket ID </th>";
echo "<th class=tableHeader> Due Date </th>";
echo "<th class=tableHeader> Assignee </th>";
echo "</tr>";


while($row1NextWeek = mssql_fetch_array($resultDueNextWeek))
{

        if (strtotime($row1NextWeek['DueDate']) <= strtotime($now)) {

                echo "<tr>";
                echo "<td class=pastDueDate>" . $row1NextWeek['TicketID'] . "</td>";
                echo "<td class=pastDueDate>" . $row1NextWeek['DueDate'] . "</td>";
                echo "<td class=pastDueDate>" . $row1NextWeek['FullName'] . "</td>";
                echo "</tr>";
	
	// Modulo 2 will separate even and odd numbers. This is used to display striped rows.
        } elseif ($row1NextWeek['rowID'] % 2 == 0) {

                echo "<tr>";
                echo "<td class=evenTableLine>" . $row1NextWeek['TicketID'] . "</td>";
                echo "<td class=evenTableLine>" . $row1NextWeek['DueDate'] . "</td>";
                echo "<td class=evenTableLine>" . $row1NextWeek['FullName'] . "</td>";
                echo "</tr>";
        
        } else {

                echo "<tr>";
                echo "<td class=oddTableLine>" . $row1NextWeek['TicketID'] . "</td>";
                echo "<td class=oddTableLine>" . $row1NextWeek['DueDate'] . "</td>";
                echo "<td class=oddTableLine>" . $row1NextWeek['FullName'] . "</td>";
                echo "</tr>";

        }
}

echo "</table><br>";

$testDate = date("Y-m-d H:i:s");

echo "testDate: " . $testDate . "<br>";
echo date('Y-m-d', strtotime($testDate. ' + 5 days'));


//Close the connection
mssql_close($dbhandle);

?>

</body>
</html>
