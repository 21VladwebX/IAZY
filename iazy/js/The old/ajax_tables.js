$(function(){
	var $i = 0;
	$('#submit').on('click',function(){
		$i++;
		if ($i < 2){
			//$('#img').css('display: inline;');
			setTimeout(function() {	
				$('#tables').replaceWith('<span src = "../php/tables.php" > </span>');
			},10000);
		};
	});
});