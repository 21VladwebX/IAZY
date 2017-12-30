$(function(){
	$('#mainForm').on('submit',
	function(e){
		e.preventDefault(); //отключает стандартное действие
		var $that=$(this),
		formData = new FormData($that.get(0)); //кодирует форму для отправки на сервер // берет берет данные с текущей формы со всех её полей
		$.ajax({
			url: $that.attr('action'),
			type: $that.attr('method'),
			contentType: false,
			processData: false,
			data: formData,
			dataType: 'json',
			success: function(){
								
					$('#graphic').replaceWith("<img src = './php/graph.php' />");				//!
					
					//alert (data.tr);
					//console.log(dat.tr);
					
					$.ajax({
						url: './php/tables.php',
						type: 'post',
						contentType: false,
						processData: false,
						data: formData,
						dataType: 'json',
						success: function(json){
							 if(json){
							$('#tables').replaceWith("<div id = table> " + json + "</div>");					
							 }
						}
					});
			 }
		});
	});
});
	
	// var $i = 0,$d = 0;
	// $('#submit').on('click',function(){
		// $i++;
		// if ($i < 2){
			// $('#img').css('display: inline;');
			// if($d == 0 ){
				// setInterval( function() {
				// if(dat.tr == true){	
					// $('#graph').replaceWith('<img src = "../php/graph.php" />');				//!
					// $('#tables').replaceWith('<p> <php  include "../php/tables.php" </p>'); 	//!
					// $d++;
					// }
				// },1000);
			// };
		// };
	// });
	
	//alert ('Подключён, начинаю работу!');





