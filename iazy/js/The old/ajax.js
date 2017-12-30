$(function(){
	$('#mainForm').on('submit',
	function(e){
		e.preventDefault(); //отключает стандартное действие
		var $that=$(this),
		formData = new FormData($that.get(0)); //кодирует форму для отправки на сервер
		$.ajax({
			url: $that.attr('action'),
			type: $that.attr('method'),
			contentType: false,
			processData: false,
			data: formData,
			dataType: 'json',
			success: function(json){
				if(json){
					$('#res').replaceWith('<div id = "res">'+json+'</div>'); //??
				}
			}
		});
	});
	//alert ('Подключён, начинаю работу!');
 });

//alert ('ajax');

