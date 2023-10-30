// JavaScript Document
function formsubmit()
{
    //alert("sucess");
    var form = $('#submitform');
    var error = $('#form-error');
    var field = $('#dealerid');
   var field = $('#DPC_attachfromdate');
    if(!field.val()) { error.html(errormessage("Enter the From Date.")); field.focus(); return false; }
    var field = $('#DPC_attachtodate');
    if(!field.val()) { error.html(errormessage("Enter the To Date.")); field.focus(); return false; }
    var values = validateproductcheckboxes();
    if(values == false)	{error.html(errormessage("Select A Product")); return false;	}
    else
    {
        error.html('');
        //alert("sucess1");
        $('#submitform').attr("action", "../reports/exceltransferredpinsreport.php") ;
        $('#submitform').attr( 'target', '_blank' );
        $('#submitform').submit();

    }
}


function selectdeselectall(showtype)
{
    var selectproduct = $('#selectproduct');
    var chkvalues = $("input[name='productname[]']");
    if(showtype == 'one')
    {
        for (var i=0; i < chkvalues.length; i++)
        {
            if($(chkvalues[i]).is(':checked'))
            {
                $(chkvalues[i]).attr('checked',false);
            }
            if( $('#selectproduct').val() == 'ALL')
                $(chkvalues[i]).attr('checked',true);
            else if(selectproduct.value == 'NONE')
                $(chkvalues[i]).attr('checked',true);
            else if(chkvalues[i].getAttribute('producttype') == $('#selectproduct').val())
            {
                $(chkvalues[i]).attr('checked',true);
                $('#groupvalue').val('');
            }
        }
    }else if(showtype == 'more')
    {

        var addproductvalue = $("#selectproduct option:selected").val();
        if($('#groupvalue').val() == '')
            $('#groupvalue').val($('#groupvalue').val() +  addproductvalue);
        else
            $('#groupvalue').val($('#groupvalue').val() + '%' +  addproductvalue);
        for (var i=0; i < chkvalues.length; i++)
        {
            if($(chkvalues[i]).is(':checked'))
            {
                $(chkvalues[i]).attr('checked',false);
            }

            var var1 = $('#groupvalue').val().split('%');
            for( var j=0; j<var1.length; j++)
            {
                if($('#selectproduct').val() == 'ALL')
                    $(chkvalues[i]).attr('checked',true);
                else if($('#selectproduct').val() == 'NONE')
                    $(chkvalues[i]).attr('checked',false);
                if(chkvalues[i].getAttribute('producttype') == var1[j])
                {
                    $(chkvalues[i]).attr('checked',true);
                }
            }
        }
    }
}


function validateproductcheckboxes()
{
    var chksvalue = $("input[name='productname[]']");
    var hasChecked = false;
    for (var i = 0; i < chksvalue.length; i++)
    {
        if ($(chksvalue[i]).is(':checked'))
        {
            hasChecked = true;
            return true
        }
    }
    if (!hasChecked)
    {
        return false
    }
}