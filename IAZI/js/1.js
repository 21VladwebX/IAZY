$(function(){
	$('#mainForm').on('submit',
	function(e){
		e.preventDefault(); //отключает стандартное действие
		var $that=$(this),
		formData = new FormData($that.get(0)); //кодирует форму для отправки на сервер 
		$.ajax({
			url: $that.attr('action'),
			type: $that.attr('method'),
			contentType: false,  //Указываем формат, в котором бужем передавать данные
			processData: false,
			data: formData,
			dataType: 'json',
			success: function(json){
				$('#graph').replaceWith("<img src = './php/graph.php' />"); 
				$('#tables').replaceWith("<div id = 'tables'>" + json + "</div>");
			}
		});
	});
});
	
	