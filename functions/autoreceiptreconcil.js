$(document).ready(function(){
$("#submitform").on('submit',(function(e) {
        e.preventDefault();
        var bar = $('.bar');
		var percent = $('.percent');
		var status = $('#status');
        var data1=new FormData(this);
       data1.append('switchtype', 'checkuploaddata');
        $.ajax({
            url: "../ajax/autoreceipt.php",
            type: "POST",
            data: data1,
            contentType: false,
            //dataType: json,
            cache: false,
            processData:false,

           target:   '#targetLayer', 
				beforeSubmit: function() {
				  status.empty();
        			var percentVal = '0%';
        			bar.width(percentVal)
        			percent.html(percentVal);
				},
				uploadProgress: function (event, position, total, percentComplete){	
					var percentVal = percentComplete + '%';
        bar.width(percentVal)
        percent.html(percentVal);
				},

            success: function(response)
            {
               // var data = [tabledata];
                //alert("success");
                
                var ajaxresponse = response.split('^');
                //alert(ajaxresponse[0]);
                if(ajaxresponse[0] == 1)
                {
                    // alert(ajaxresponse[2]);
                    bar.width("100%");
                    percent.html("100%");
                    $('#receiptcountid').html("Total Count :" + ajaxresponse[2]);
                    //var res = jQuery.unique(ajaxresponse[1]);
                    $("#tabgroupgridc1_1").html(ajaxresponse[1]);
                    $('#griddata').DataTable();

                    //$('#resultgrid').html($('#tabgroupgridc1_1').html());
                    //$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
                 }
                 else if(ajaxresponse[0] == 2)
                 {
                    alert(ajaxresponse[1]);
                 }
                 else
                {
                    $('#tabgroupgridc1_1').html("No datas found to be displayed.");
                }
         },
            complete: function(xhr) {
            //status.html(xhr.responseText);
            },
            error: function(data)
            {
                 alert("error");
                //console.log(data);
            }
        });
    }));
    });