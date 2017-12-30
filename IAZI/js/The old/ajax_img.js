$(function(){
	var $i = 0;
	$('#submit').on('click',function(){
		$i++;
		if ($i < 2){
			//$('#img').css('display: inline;');
			setTimeout(function() {	
				$('#graph').replaceWith('<img src = "../php/graph.php" />');
			},10000);
		};
	});
});
//alert ('ajax_img');
