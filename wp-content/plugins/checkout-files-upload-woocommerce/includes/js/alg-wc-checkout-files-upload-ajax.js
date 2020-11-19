/**
 * alg-wc-checkout-files-upload-ajax.js
 *
 * @version 1.4.1
 * @since   1.3.0
 * @author  Algoritmika Ltd.
 * @todo    [dev] maybe validate file type with `upload.getType()` (same as `upload.getSize()`)
 * @todo    [feature] text messages (e.g. `span` or `div`) instead of `alert()`
 */

var Upload = function (file) {
	this.file = file;
};
Upload.prototype.getType = function() {
	return this.file.type;
};
Upload.prototype.getSize = function() {
	return this.file.size;
};
Upload.prototype.getName = function() {
	return this.file.name;
};
Upload.prototype.doUpload = function (file_num) {
	var formData = new FormData();
	formData.append("file", this.file, this.getName());
	formData.append("action", "alg_ajax_file_upload");
	formData.append("file-num", file_num);
	formData.append("order_id", jQuery("#alg_checkout_files_upload_order_id_"+file_num).val());
	formData.append("key", jQuery("#alg_checkout_files_upload_order_key_"+file_num).val());
	jQuery.ajax({
		type: "POST",
		url: ajax_object.ajax_url,
		xhr: function () {
			var xhr = jQuery.ajaxSettings.xhr();
			if (alg_wc_checkout_files_upload.progress_bar_enabled) {
				if (xhr.upload) {
					xhr.upload.file_num = file_num;
					xhr.upload.addEventListener('progress', function(evt){
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total * 100;
							var progress_bar_id = "#alg-wc-checkout-files-upload-progress-wrapper-"+event.target.file_num;
							jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-bar").css("width", +percentComplete + "%");
							jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-status").text(percentComplete + "%");
						}
					}, false);
				}
			}
			return xhr;
		},
		success: function (data) {
			var data_decoded = jQuery.parseJSON(data);
			var question = document.getElementsByClassName("question-header");
			var question_content = document.getElementsByClassName("cp-acf-fw-form-groups");

			if ( 0 != data_decoded['result'] ) {
				jQuery("#alg_checkout_files_upload_"+file_num).hide();
				jQuery("#alg_checkout_files_upload_result_"+file_num).show();
				jQuery("#alg_checkout_files_upload_result_file_name_"+file_num).html(data_decoded['data']);
				if (typeof data_decoded['data_img'] !== 'undefined' && data_decoded['data_img'] !== false && data_decoded['data_img'] !== '') {
					jQuery("#alg_checkout_files_upload_image_"+file_num).html(data_decoded['data_img']);
				} else {
					jQuery("#alg_checkout_files_upload_image_"+file_num).empty();
				}
			} else {
				document.getElementById( 'alg_checkout_files_upload_'+file_num ).value = null;
				jQuery("#alg_checkout_files_upload_result_file_name_"+file_num).text("");
				jQuery("#alg_checkout_files_upload_image_"+file_num).empty();
				if (alg_wc_checkout_files_upload.progress_bar_enabled) {
					var progress_bar_id = "#alg-wc-checkout-files-upload-progress-wrapper-"+file_num;
					jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-bar").css("width", "0%");
					jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-status").text("0%");
				}
			}
			if (''!=data_decoded['message']) {
				alert(data_decoded['message']);
					if (document.getElementById("_cpb_1582021098412") != null) {
						document.getElementById("_cpb_1582021098412").required = false;
					}
					if (document.getElementById("_cpb_1582021067534") != null) {
						document.getElementById("_cpb_1582021067534").required = false;
					}
					if (document.getElementById("_cpb_1582021011630") != null) {
						document.getElementById("_cpb_1582021011630").required = false;
					}
					if (document.getElementById("_cpb_1582020985669")) {
						document.getElementById("_cpb_1582020985669").required = false;
					}
					if (document.getElementById("_cpb_1582020054677") != null) {
						document.getElementById("_cpb_1582020054677").required = false;
					}
					if (document.getElementById("_cpb_1582019952550") != null) {
						document.getElementById("_cpb_1582019952550").required = false; 
					}
					if (document.getElementById("_cpb_1586422045730") != null) {
						document.getElementById("_cpb_1586422045730").required = false; 
					}
					if (document.getElementById("_cpb_1586846590766") != null) {
						document.getElementById("_cpb_1586846590766").required = false; 
					}
					if (document.getElementById("_cpb_1586422368990") != null) {
						document.getElementById("_cpb_1586422368990").required = false; 
					}			
					if (document.getElementById("_cpb_1582013439715") != null) {
						document.getElementById("_cpb_1582013439715").required = false; 
					}			
					if (document.getElementById("_cpb_1581341086621") != null) {
						document.getElementById("_cpb_1581341086621").required = false; 
					}
					if (document.getElementById("_cpb_1581340760805") != null) {
						document.getElementById("_cpb_1581340760805").required = false; 
					}
					if (document.getElementById("_cpb_1582024047400") != null) {
						document.getElementById("_cpb_1582024047400").required = false; 
					}
					if (document.getElementById("_cpb_1582024072980") != null) {
						document.getElementById("_cpb_1582024072980").required = false; 
					}
					if (document.getElementById("_cpb_1582024098692") != null) {
						document.getElementById("_cpb_1582024098692").required = false; 
					}
				question[0].style.display = 'none';
				question_content[0].style.display = 'none';
			}
		},
		error: function (error) {},
		async: true,
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		timeout: 60000
	});
};

jQuery(document).ready(function() {
	jQuery(".alg_checkout_files_upload_file_input").on("change", function (e) {
		var file = jQuery(this)[0].files[0];
		var upload = new Upload(file);
		var max_file_size = parseInt(alg_wc_checkout_files_upload.max_file_size);
		if (max_file_size > 0 && upload.getSize() > max_file_size) {
			alert(alg_wc_checkout_files_upload.max_file_size_exceeded_message);
			document.getElementById( 'alg_checkout_files_upload_'+jQuery(this).attr('file-num') ).value = null;
		} else {
			upload.doUpload(jQuery(this).attr('file-num'));
		}
	});
	jQuery(".alg_checkout_files_upload_result_delete").on("click", function (e) {
		e.preventDefault();
		var file_num = jQuery(this).attr('file-num');
		var formData = new FormData();
		formData.append("action", "alg_ajax_file_delete");
		formData.append("file-num", file_num);
		formData.append("order_id", jQuery("#alg_checkout_files_upload_order_id_"+file_num).val());
		jQuery.ajax({
			type: "POST",
			url: ajax_object.ajax_url,
			success: function (data) {
				var data_decoded = jQuery.parseJSON(data);
				var question = document.getElementsByClassName("question-header");
				var question_content = document.getElementsByClassName("cp-acf-fw-form-groups");

				if ( 0 != data_decoded['result'] ) {
					document.getElementById( 'alg_checkout_files_upload_'+file_num ).value = null;
					jQuery("#alg_checkout_files_upload_"+file_num).show();
					jQuery("#alg_checkout_files_upload_result_"+file_num).hide();
					jQuery("#alg_checkout_files_upload_result_file_name_"+file_num).text("");
					jQuery("#alg_checkout_files_upload_image_"+file_num).empty();
					if (alg_wc_checkout_files_upload.progress_bar_enabled) {
						var progress_bar_id = "#alg-wc-checkout-files-upload-progress-wrapper-"+file_num;
						jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-bar").css("width", "0%");
						jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-status").text("0%");
					}
				}
				if (''!=data_decoded['message']) {
					alert(data_decoded['message']); 
						if (document.getElementById("_cpb_1582021098412") != null) {
							document.getElementById("_cpb_1582021098412").required = true;
						} 
						if (document.getElementById("_cpb_1582021067534") != null) {
							document.getElementById("_cpb_1582021067534").required = true;
						}
						if (document.getElementById("_cpb_1582021011630") != null) {
							document.getElementById("_cpb_1582021011630").required = true;
						}
						if (document.getElementById("_cpb_1582020985669")) {
							document.getElementById("_cpb_1582020985669").required = true;
						}
						if (document.getElementById("_cpb_1582020054677") != null) {
							document.getElementById("_cpb_1582020054677").required = true;
						}
						if (document.getElementById("_cpb_1582019952550") != null) {
							document.getElementById("_cpb_1582019952550").required = true; 
						}
						if (document.getElementById("_cpb_1586422045730") != null) {
							document.getElementById("_cpb_1586422045730").required = true; 
						}
						if (document.getElementById("_cpb_1586846590766") != null) {
							document.getElementById("_cpb_1586846590766").required = true; 
						}
						if (document.getElementById("_cpb_1586422368990") != null) {
							document.getElementById("_cpb_1586422368990").required = true; 
						}			
						if (document.getElementById("_cpb_1582013439715") != null) {
							document.getElementById("_cpb_1582013439715").required = true; 
						}			
						if (document.getElementById("_cpb_1581341086621") != null) {
							document.getElementById("_cpb_1581341086621").required = true; 
						}
						if (document.getElementById("_cpb_1581340760805") != null) {
							document.getElementById("_cpb_1581340760805").required = true; 
						}
						if (document.getElementById("_cpb_1582024047400") != null) {
							document.getElementById("_cpb_1582024047400").required = true; 
						}
						if (document.getElementById("_cpb_1582024072980") != null) {
							document.getElementById("_cpb_1582024072980").required = true; 
						}
						if (document.getElementById("_cpb_1582024098692") != null) {
							document.getElementById("_cpb_1582024098692").required = true; 
						}
					question[0].style.display = '';
					question_content[0].style.display = '';
				}
			},
			error: function (error) {},
			async: true,
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			timeout: 60000
		});
	});
});