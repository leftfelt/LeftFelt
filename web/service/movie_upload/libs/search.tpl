<div align='center' id='search'>
	<form method='get' action='search.php'>
	<input type='search' name='keyword' id='keyword' value='{{$smarty.get.keyword}}'>
	<input type='button' value='検索' onclick="searchKeyword();">
	<input type='button' value='タグ検索' onclick="searchTag();">
	</form>
</div>

