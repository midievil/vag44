var correctLabel = '<img src="/img/ok.png" >';
var incorrectLabel = '<img src="/img/error.png" >';

function checkUserName(userName)
{
	if(userName=="" || userName.length< 4)
	{
		isValid = false;
		showPassword();
		return;
	}	
	else{
		$.ajax({	type: "POST",	
					url:"/response/userresponse.php",
					data: "action=checkname&name="+userName,
					success: function(result){
						if(trim(result) == "0")
						{
							isValid = false;
							$("#tdregisterNickCheck").html(incorrectLabel);
							showPassword();
						}
						else
						{
							isValid = true;
							$("#tdregisterNickCheck").html(correctLabel);
							showPassword();							
						}
					}
			});
	
	}
}

function checkPassword(userName)
{
	var password1 = $("#tbRegisterPassword").val();
	var password2 = $("#tbRegisterPasswordConfirm").val();
	
	if(password1 == password2 && password1.length>=8)
	{
		isValid = true;
		$("#tdregisterPasswordCheck").html(correctLabel);
		$("#tdregisterPasswordConfirmCheck").html(correctLabel);
	}
	else	
	{
		isValid = false;
		$("#tdregisterPasswordCheck").html(incorrectLabel);
		$("#tdregisterPasswordConfirmCheck").html(incorrectLabel);
	}
	showEmail();
}

function logout()
{
	$.ajax({	type: "POST",
				url: "/response/userresponse.php",
				data: "action=logout"
			}
		);
	window.setTimeout(function() {	window.location = '/'; }, 500);
}

function cancelLogin(){
	$("#divLogin").hide();
	$("#tbLoginName").val("");
	$("#tbLoginPass").val("");
}   

function checkLogin()
{		
	$.ajax({	type: "POST",
				url: "/response/userresponse.php",
				data: "action=checklogin&name="+$("#tbLoginName").val()+"&pass="+$("#tbLoginPass").val(),
				success: function(result){
					if(trim(result) == 'ok')
					{		
						window.setTimeout(function() {	window.location = window.location; }, 500);						
					}
					else
					{		
						//$("#divLogin").height(50);
						alert("Что-то введено неверно. Попробуйте еще раз!");
					}
				}
			});
	
}

function showPersonalInfo()
{
	$.ajax({	type: "POST",
				url: "/response/userresponse.php",
				data: "action=showpersonalinfo",
				success:	function(result) 
							{
								if(trim(result) == "notlogged")
								{
									$("#divPersonalinfo").hide();
								}
								else
								{
									//console.log(result);
									$("#divPersonalinfo").html(result);
									$("#divPersonalinfo").show();
								}
							}
			}
		);
}

function thankUser(postID, commentID, userID)
{
	$.ajax({	type: "POST",
				url: "/response/userresponse.php",
				data: "action=thankuser&userid="+userID+"&postid="+postID+"&commentid="+commentID,
				success:	function(result) 						
							{
								if(trim(result) == "self")
								{
									alert("Вы не можете благодарить самого себя");
								}
								else if(trim(result) == "commentthanked")
								{
									if(commentID == -1)
									{
										alert('Вы уже повышали рейтинг пользователя за этот пост');
									}
									else
									{
										alert('Вы уже повышали рейтинг пользователя за этот комментарий');
									}
								}
								else if(trim(result) == "postthanked")
								{
									alert('Вы уже повышали рейтинг пользователя за этот пост');
								}
								else if(trim(result) == "ok")
								{
									if(commentID != -1)
									{
										$("#aCommentIncrease" + commentID).hide();
										var currentRating = $("#divCommentRating" + commentID).text();
										$("#divCommentRating" + commentID).text((currentRating*1)+1);
									}
									else
									{
										$("#divPostIncreaseRating").hide();
										var currentRating = $("#divPostRating").text();
										$("#divPostRating").text((currentRating*1)+1);
									}
								}
								else
								{
									alert('Что-то пошло не так. Пожалуйста, попробуйте чуть позже или напишите в обратную связь');
								}
							}
			}
		);
}


function restorePassword(email)
{
	email = trim(email);
	if(email == "")
	{
		alert('Всё же сначала укажите адрес почты');
		return;
	}
	
	$.ajax({	type: "POST",
				url: "/response/userresponse.php",
				data: "action=restorepassword&email="+email,
				success:	function(result) 	
				{
					if(trim(result) == 'sent')
					{
						$("#divSendForm").hide();
						$("#divSentForm").show();
					}
					else if(trim(result) == 'nosuchemail')
					{
						alert('Указанный вами адрес отсутствует в списке пользователей. Наверное, вы указали другой адрес при регистрации или опечатались.');
					}
					else
					{
						alert('Кажется, что-то сломалось. Администратор уведомлен об ошибке, но вы можете написать в обратную связь.');
					}
				}
			});
}

function changePass(oldPass, newPass, confirmPass)
{
	oldPass = trim(oldPass);
	newPass = trim(newPass);
	
	if(oldPass == "")
	{
		alert("Укажите старый пароль");
		return;
	}
	else if(newPass.length < 8)
	{
		alert("Пароль должен быть не короче восьми символов");
		return;
	}
	else if(newPass != confirmPass)
	{
		alert("Новый пароль и подтверждение не совпадают");
		return;
	}
	$.ajax({	type: "POST",
				url: "/response/userresponse.php",
				data: "action=changepassword&oldpass="+oldPass+"&newpass="+newPass,
				success:	function(result) 	
				{
					if(trim(result) == 'wrongpass')
					{
						alert("Неверно указан старый пароль");
					}
					else if(trim(result) == 'ok')
					{
						$("#aComment").text("Пароль изменен");						
					}
					else
					{
						alert('Кажется, что-то сломалось. Администратор уведомлен об ошибке, но вы можете написать в обратную связь.');
					}
				}
			});
}	

function markOnline(userid)
{
	$.ajax({	type: "POST",
				url: "/response/userresponse.php",
				data: "action=markonline&userid="+userid,
				success:	function(result) 	{		}
			});
	setTimeout(function() { markOnline(userid); }, 1000*60);
}