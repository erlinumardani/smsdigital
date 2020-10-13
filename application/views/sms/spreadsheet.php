<!DOCTYPE html>
<html>
<head>
	<title>SMS Data Historical</title>
</head>
<body>
	<style type="text/css">
	body{
		font-family: sans-serif;
	}
	table{
		margin: 20px auto;
		border-collapse: collapse;
	}
	table th,
	table td{
		border: 1px solid #3c3c3c;
		padding: 3px 8px;
 
	}
	a{
		background: blue;
		color: #fff;
		padding: 8px 10px;
		text-decoration: none;
		border-radius: 2px;
	}
	</style>
 
	<?php
	header($appconfig);
	header($fileconfig);
	?>
 
	<center>
		<h1>SMS Data Historical</h1>
    </center>
 
	<table border="1">
		<tr>
            <th>Type</th>
            <th>Phone Number</th>
            <th>Message</th>
            <th>Sender</th>
            <th>Provider</th>
            <th>Status</th>
            <th>Reason</th>
            <th>MSGID</th>
            <th>Schedule</th>
            <th>Date Created</th>
		</tr>

        {data}
		<tr>
			<td>{type}</td>
			<td>{msisdn}</td>
			<td>{message}</td>
			<td>{sender}</td>
			<td>{provider}</td>
			<td>{status}</td>
			<td>{reason}</td>
			<td>{guid}</td>
			<td>{schedule}</td>
			<td>{created_at}</td>
        </tr>
        {/data}
		
	</table>
</body>
</html>