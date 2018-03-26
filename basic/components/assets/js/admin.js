$(function(){
	$('.ajax-form-submit').click(function(){
    	var that = $(this);
		var form = $('.ajax-form');
		showLoading(_message.loading);
		$.ajax({
	        type: 'post',
	        url: form.data('action'),
	        data: form.serialize(),
	        dataType: 'json',
	        error: function(xhr) {
	        	hideLoading();
				if(xhr.status == '403'){
					showError(_message.errorHttp403);
				}else if(xhr.status == '404'){
					showError(_message.errorHttp404);
				}else{
					showError(_message.errorHttp500);
				}
	        },
	        success: function(result) {
	        	hideLoading();
		        if(result.result){
					if(result.result === true || result.result == 1){
						showError(result.message);
					}
					if(result.result == '@'){
						history.back();
					}else if(result.result.substr(0, 1) == '/'){
			        	location.href = result.result;
					}
		        }else{
		        	showError(result.message);
		        }
	        }
	    });
	});
	
	$('.ajax-table-delete').on('click', function(){
		var that = $(this);
		var father = that.parent().parent();
		showConfirm(_message.confirmDeleteTitle, _message.confirmDeleteTip, function (isConfirm) {
            if (isConfirm) {
            	showLoading(_message.loading);
            	$.ajax({
    				url: that.data('action'),
    				type: 'post',
    				data: {'id':father.data('key'), '_csrf':_csrf},
    				dataType: 'json',
    				success: function(result){
    					hideLoading();
    					if(result.result){
    						father.fadeOut('slow');
    					}else{
    						showError(result.message);
    					}  
    				},  
    				error: function(xhr) {
    					hideLoading();
    					if(xhr.status == '403'){
    						showError(_message.errorHttp403);
    					}else if(xhr.status == '404'){
    						showError(_message.errorHttp404);
    					}else{
    						showError(_message.errorHttp500);
    					}
    				}  
    			});
            } else {
                
            }
        }, {confirmButtonText: _message.confirmDelete, cancelButtonText: _message.confirmCancel, width: 400});
	});
	
	$('.ajax-file-button').click(function(){
		$('.ajax-file-input').click();
	});
	
	$('.ajax-file-input').change(function(){
		var that = $(this);
		if(!that.val()){
			return false;
		}
		var formData = new FormData();
		formData.append('UploadForm[file]', that.get(0).files[0]); 
		$.each(that.data(), function(k, v){
			formData.append('Data['+ k +']', v); 
		});
		showLoading(_message.loading);
		$.ajax({
			url: that.data('action'),
			type: 'post',
			timeout: 600000,
			data: formData,
			contentType: false,
			processData: false,
			success: function(result){
				hideLoading();
				if (result.result) {
					ajax_file_success(result);
				}else{
					showError(result.message);
				}  
			},  
			error: function(xhr) {
				hideLoading();
				if(xhr.status == '403'){
					showError(_message.errorHttp403);
				}else if(xhr.status == '404'){
					showError(_message.errorHttp404);
				}else{
					showError(_message.errorHttp500);
				}
			}  
		});
	});
});