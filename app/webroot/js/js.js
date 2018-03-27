function check_details(){
	if(document.form1.Email.value==''){
		alert('Please enter a user id');
		document.form1.Email.focus();
		return false;
	}
	if(document.form1.Passwd.value==''){
		alert('Please enter a user password');
		document.form1.Passwd.focus();
		return false;
	}
	return true;
}

function frm_sbmt(frm_id,div_id,pg_url){
	$("#"+div_id).hide();
	$("#loader").show();
	var srt = $("#"+frm_id).serialize();
	$.ajax({
		type: 'POST',
		url: pg_url,
		data: srt,
		success: function(d) {
			$("#"+div_id).html(d);
		}
	});
	$("#loader").hide();
	$("#"+div_id).fadeIn(1000);
	return false;
}

function load_pg(divop,pgop){
	$("#"+divop).hide();
	$("#loader").show();
	$("#"+divop).load(pgop,function(){
		$("#loader").hide();
		$("#"+divop).fadeIn(1000);
	});
}

function show_popup(selmnt,shwpg){
	if(selmnt!=''){
		var x= document.getElementsByName(selmnt);
		for(var k=0;k<x.length;k++)
		if(x[k].checked){
			var temp = x[k].value;
		}
		var pglink=shwpg+temp;
	}else{
		var pglink=shwpg;
	}
	$("#popup_div").show();
	$("#popuppage").hide();
	$("#puloader").show();
	$("#popuppage").load(pglink,function(){
		$("#puloader").hide();
		$("#popuppage").show();
	});
}

function close_popup(){
	$("#popup_div").hide();
}

function filterx (term, _id, cellNr){
	var searchText = term.value.toLowerCase();
    var targetTable = document.getElementById(_id);
    var targetTableColCount;

    for (var rowIndex = 0; rowIndex < targetTable.rows.length; rowIndex++) {
        var rowData = '';

        if (rowIndex == 0) {
           targetTableColCount = targetTable.rows.item(rowIndex).cells.length;
           continue;
        }

        for (var colIndex = 0; colIndex < targetTableColCount; colIndex++) {
            rowData += targetTable.rows.item(rowIndex).cells.item(colIndex).textContent;
        }

		rowData = rowData.toLowerCase();

        if (rowData.indexOf(searchText) == -1)
            targetTable.rows.item(rowIndex).style.display = 'none';
        else
            targetTable.rows.item(rowIndex).style.display = 'table-row';
    }
}

function del(id,table_name,primary_key,field,divert_page)
{
	var x= document.getElementsByName(id);
	for(var k=0;k<x.length;k++)
		if(x[k].checked){
			var temp = x[k].value;
		}
	show_popup('','delete.php?tablename='+ table_name +'&primarykey='+ primary_key +'&field='+ field +'&primarykeyvalue='+ temp +'&divertpage='+ divert_page);
}

var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()

//For Client Registration Page START
function val_frm_sbmt_clnt_reg(frm_id,div_id,pg_url){
	var flg = 0;
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if($('#fname').val()==''){
		alert ('First name can not be blank ... ');
		flg = 1;
	}
	if($('#sname').val()==''){
		alert ('Last name can not be blank ... ');
		flg = 1;
	}
	if($('#uname').val()==''){
		alert ('User name can not be blank ... ');
		flg = 1;
	}
	if($('#passwor').val()==''){
		alert ('Password can not be blank ... ');
		flg = 1;
	}
	if($('#password').val()!=$('#repassword').val()){
		alert ('Password does not match ... ');
		flg = 1;
	}
	if($('#email').val()==''){
		alert ('Email can not be blank ... ');
		flg = 1;
	}
	if($('#phone').val()==''){
		alert ('Phone Number can not be blank ... ');
		flg = 1;
	}
	if($('#address').val()==''){
		alert ('Address can not be blank ... ');
		flg = 1;
	}
        if($('#email').val()==''){
		alert ('Email can not be blank ... ');
		flg = 1;
	}
       	if(flg==0){
		frm_sbmt(frm_id,div_id,pg_url);
	}
}
//For Client Registration Page STOP

//For Forget Page START

	function val_frm_sbmt_for_reg(frm_id,div_id,pg_url){
		var flg = 0;
		if($('#uname').val()==''){
			alert ('User name can not be blank ... ');
			flg = 1;
		}
		if($('#email').val()==''){
			alert ('User Email can not be blank ... ');
			flg = 1;
		}
		if($('#phone').val()==''){
			alert ('User Phone can not be blank ... ');
			flg = 1;
		}
		if(flg==0){
			frm_sbmt(frm_id,div_id,pg_url);
		}
	}
//For Forget Page STOP

//For Login Page START

function val_frm_sbmt_log_reg(frm_id,div_id,pg_url){
		var flg = 0;
		if($('#Email').val()==''){
			alert ('User name can not be blank ... ');
			flg = 1;
		}
		if($('#Passwd').val()==''){
			alert ('Password can not be blank ... ');
			flg = 1;
		}
		if(flg==0){
			frm_sbmt(frm_id,div_id,pg_url);
		}
	}


/*

	function val_frm_sbmt_log_reg(frm_id,div_id,pg_url){
		var flg = 0;
		if($('#uname').val()==''){
			alert ('User name can not be blank ... ');
			flg = 1;
		}
		if($('#password').val()==''){
			alert ('Password can not be blank ... ');
			flg = 1;
		}
		if(flg==0){
			frm_sbmt(frm_id,div_id,pg_url);
		}
	}
*/

//For Login Page STOP

//For Client Setting Page START

		//For Change Password Section START
	
		function val_frm_sbmt_chg_pass(frm_id,div_id,pg_url){
			var flg = 0;
			if($('#opass').val()==''){
				alert ('Old Password can not be blank ... ');
				flg = 1;
			}
			if($('#npass').val()==''){
				alert (' New Password can not be blank ... ');
				flg = 1;
			}
			if($('#npass').val()!=$('#repass').val()){
				alert ('Password does not match ... ');
				flg = 1;
			}
		}
	//For Change Password Section STOP
	
	//For Other Updates Section START
	
		function val_frm_sbmt_chg_pass(frm_id,div_id,pg_url){
			var flg = 0;
			if($('#nl').val()==''){
				
				flg = 1;
			}
			if($('#dn').val()==''){
				
				flg = 1;
			}
			if($('#du').val()!=''){
				
				flg = 1;
			}
			if(flg==0){
			frm_sbmt(frm_id,div_id,pg_url);
		}
		}
	//For Other Updates Section STOP

//For Client Setting Page STOP

function echeck(str) {

		var at="@";
		var dot=".";
		var lat=str.indexOf(at);
		var lstr=str.length;
		var ldot=str.indexOf(dot);
		if (str.indexOf(at)==-1){
		   alert("Invalid E-mail ID");
		   return false;
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   alert("Invalid E-mail ID");
		   return false;
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    alert("Invalid E-mail ID");
		    return false;
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    alert("Invalid E-mail ID");
		    return false;
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    alert("Invalid E-mail ID");
		    return false;
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    alert("Invalid E-mail ID");
		    return false;
		 }
		
		 if (str.indexOf(" ")!=-1){
		    alert("Invalid E-mail ID");
		    return false;
		 }

 		 return true;			
	}

function ValidateForm(){
	
	return true
 }
