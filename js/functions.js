function checkNumber(input)
{
	return !isNaN(input);
}

function checkDate(input)
{
	var date_array = input.split(".");
	
	if(date_array.length < 3)
	{
		return false;
	}
	
	var year = date_array[2];
	var month = date_array[1];
	var day = date_array[0];
	
	if(!checkNumber(year) || !checkNumber(month) || !checkNumber(day))
	{
		return false;
	}
	
	var d = new Date()
	var curr_year = d.getFullYear();
	var curr_month = d.getMonth()+1;
	if(year > curr_year)
	{	
		return false;
	}		
	
	if(month > 12 || month < 1 || (year == curr_year && month > curr_month))
	{
		return false;
	}
	
	var maxDay = -1;
	switch(month*1)
	{
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			maxDay = 31;
			break;
		case 2:
			maxDay =  year % 4 == 0 ? 29 : 28;
			break;
		default:
			maxDay = 30;
			break;
	}
	
	if(day < 1 || day > maxDay)
	{
		return false;
	}
	
	return true;
}

function Redirect(url)
{
	window.location="/"+url;
}

function GoToCars(marque)
{
	window.location="/"+marque;
}
