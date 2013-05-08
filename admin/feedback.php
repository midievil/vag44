<?PHP
	drawPageTitle("feedback", "Фидбэк", "");
	
	$feedbackid = $_GET["feedback"];
	$feedback = getFeedBackByID($feedbackid);
?>
<table>
	<tr>
		<td class='innercontent'>
<?PHP
	echo "<b>Суть:</b> " . $feedback["Header"] . "<br />";
	echo "<b>Описание:</b> " . $feedback["Text"] . "<br />";
?>
		</td>
	</tr>
</table>