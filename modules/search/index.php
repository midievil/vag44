<?PHP
	function getSearchString($request, $field, $searchMode)
	{
		if($searchMode == 'solid')
		{
			$result .= " $field LIKE '%$request%'";
		}
		else
		{
			$result = "1=1";
			$parts = explode(" ", $request);
			$i = 0;
			while($part = $parts[$i++])
			{
				$result .= "
					AND	$field LIKE '%$part%'";
			}	
		}
		return $result;
	}
	
	function searchInPostTitles($what,$searchMode)
	{	
		$query = "
			SELECT	P.ID PostID,
					P.*,
					U.Name	UserName,
					U.ID	UserID
			FROM	Posts P
			JOIN	Blogs B on B.ID = P.BlogID
			JOIN	Users U	on U.ID = B.UserID
			WHERE	" . getSearchString($what, "P.Title", $searchMode);		
		return fDB::fqueryAll($query);
	}
	
	function searchInPostTexts($what,$searchMode)
	{	
		$query = "
			SELECT	P.ID PostID,					
					P.*,
					U.Name	UserName,
					U.ID	UserID
			FROM	Posts P
			JOIN	Blogs B on B.ID = P.BlogID
			JOIN	Users U	on U.ID = B.UserID
			WHERE	" . getSearchString($what, "P.Text", $searchMode);
		return fDB::fqueryAll($query);
	}
	
	function searchInCategoryNames($what,$searchMode)
	{
		$query = "
			SELECT	TC.*
			FROM	TagCategories TC			
			WHERE	" . getSearchString($what, "TC.Name", $searchMode);
		return fDB::fqueryAll($query);
	}
	
	function searchInUserNames($what, $searchMode)
	{
		$query = "
			SELECT	U.*
			FROM	Users U
			WHERE	" . getSearchString($what, "U.Name", $searchMode);
		return fDB::fqueryAll($query);
	}
	
	function searchInComments($what,$searchMode)
	{
		$query = "
			SELECT	P.ID PostID,
					P.Title,
					U.Name	UserName,
					U.ID	UserID
			FROM	Comments C
			JOIN	Posts P	on P.ID = C.PostID
			JOIN	Blogs B on B.ID = P.BlogID
			JOIN	Users U	on U.ID = B.UserID
			WHERE	" . getSearchString($what, "C.Text", $searchMode) . "
			GROUP	BY	P.ID,
					P.Title,
					U.Name,
					U.ID";
		return fDB::fqueryAll($query);
	}
	
	
	
	templater::assign('includeMainMenu', true);
	
	$breadCrumbs[] = new BreadCrumb('Результаты поиска', '');
	templater::assign('breadCrumbs', $breadCrumbs);	

	$searchString = urldecode($_REQUEST['req']);
	$searchMode = $_REQUEST['mode'];
	if(empty($searchMode))
	{
		$searchMode = 'multi';
	}
	templater::assign('mode', $searchMode);
	templater::assign('req', $searchString);
	
	if(empty($searchString))
	{
		templater::assign('comment', "Вы, кажется, ничего не ввели");
		templater::assign('message', "Мы могли бы найти что-нибудь на нашем сайте, если бы вы ввели какой-нибудь текст");
	}
	else
	{	
		templater::assign('resultsFromUserNames', searchInUserNames($searchString,$searchMode));
		templater::assign('resultsFromPostTitles', searchInPostTitles($searchString,$searchMode));
		templater::assign('resultsFromPostTexts', searchInPostTexts($searchString,$searchMode));
		templater::assign('resultsFromComments', searchInComments($searchString,$searchMode));
		templater::assign('resultsFromCategoryNames', searchInCategoryNames($searchString,$searchMode));
		
		templater::assign('comment', "Здесь приведены результаты поиска по строке &laquo;$searchString&raquo;");
	}

	templater::display();
?>			
		


