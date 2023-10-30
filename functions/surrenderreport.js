// JavaScript Document
function customeridfivedigit(fieldvalue)
{
    if(fieldvalue.length != 5)
        return false;
    else if(isNaN(fieldvalue))
        return false;
    else
        return true;
}

function formsubmit(command)
{
    var form = $('#submitform');
    var error = $('#form-error');

    var field = $('#searchref');
    var field1 =  $('input[name=searchref]:checked').val();
    if(field1 == 'refslno')
    {
        var field = $('#searchinput');
        if(!field.val()) { error.html(errormessage('Enter Reference slno.')); field.focus(); return false; }
    }
    if(field1 == 'businessname')
    {
        var field = $('#searchinput');
        if(!field.val()) { error.html(errormessage('Enter Business Name.')); field.focus(); return false; }
    }
    if(field1 == 'customerid')
    {
        var field = $('#searchinput');
        if(!field.val()) { error.html(errormessage('Enter Last 5 Digit of Customer ID.')); field.focus(); return false; }
    }

    if(field1 == 'cardid')
    {
        var field = $('#searchinput');
        if(!field.val()) { error.html(errormessage('Enter Cardid ID.')); field.focus(); return false; }
        if(field.val()) { if(!customeridfivedigit(field.val())) { error.html(errormessage('Please Enter the valid cardid ID.')); field.focus(); return false; } }
    }

    if(field1 == 'maxcount')
    {
        var field = $('#maxcount');
        if(!field.val()) { error.html(errormessage('Enter Count Number.')); field.focus(); return false; }
    }

    var field = $('#DPC_surrendertodate');
    if(!field.val()) { error.html(errormessage("Enter the Surrender To Date.")); field.focus(); return false; }

    var field = $('#DPC_surrenderfromdate');
    if(!field.val()) { error.html(errormessage("Enter the Surrender From Date.")); field.focus(); return false; }

    var values = validateproductcheckboxes();
    if(values == false)	{error.html(errormessage("Select A Product")); return false;	}
    else
    {
        error.html('');
        $('#submitform').attr("action", "../reports/excelsurrenderreport.php") ;
        $('#submitform').attr( 'target', '_blank' );
        $('#submitform').submit();

    }
}


function enablesearchref()
{
    var form = $('#submitform');
    var error = $('#form-error');

    var searchref = $('input[name=searchref]:checked').val();
    if(searchref == 'all')
    {
        $('#searchrefresult').val('all');
        $('#searchinput').val('');
        $('#maxcountdiv').hide();
        $('#searchdiv').hide();
    }
    if(searchref == 'refslno')
    {
        $('#searchrefresult').val('refslno');
        $('#searchtextheader').html('Enter Reference slno ');
        $('#searchinput').val('');
        $('#maxcount').val('');
        $('#maxcountdiv').hide();
        $('#searchdiv').show();
    }
    if(searchref == 'businessname')
    {
        $('#searchrefresult').val('businessname');
        $('#searchtextheader').html('Enter Business Name ');
        $('#searchinput').val('');
        $('#maxcount').val('');
        $('#maxcountdiv').hide();
        $('#searchdiv').show();
    }
    if(searchref == 'customerid')
    {
        $('#searchrefresult').val('customerid');
        $('#searchtextheader').html('Enter Customer ID <font color="red">(last 5 digit)</font>');
        $('#searchinput').val('');
        $('#maxcount').val('');
        $('#maxcountdiv').hide();
        $('#searchdiv').show();
    }
    if(searchref == 'cardid')
    {
        $('#searchrefresult').val('cardid');
        $('#searchtextheader').html('Enter Card ID');
        $('#searchinput').val('');
        $('#maxcount').val('');
        $('#maxcountdiv').hide();
        $('#searchdiv').show();
    }
    if(searchref == 'maxcount')
    {
        $('#searchrefresult').val('maxcount');
        $('#maxcounttextheader').html('Enter Count Number');
        $('#searchinput').val('');
        $('#searchdiv').hide();
        $('#maxcountdiv').show();
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
    }
    else if(showtype == 'more')
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
