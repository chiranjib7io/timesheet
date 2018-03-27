	/*
	* Add edit delete rows dynamically using jquery and php
	* http://www.amitpatil.me/
	*
	* @version
	* 2.0 (4/19/2014)
	* 
	* @copyright
	* Copyright (C) 2014-2015 
	*
	* @Auther
	* Amit Patil
	* Maharashtra (India)
	*
	* @license
	* This file is part of Add edit delete rows dynamically using jquery and php.
	* 
	* Add edit delete rows dynamically using jquery and php is freeware script. you can redistribute it and/or 
	* modify it under the terms of the GNU Lesser General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	* 
	* Add edit delete rows dynamically using jquery and php is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	* 
	* You should have received a copy of the GNU General Public License
	* along with this script.  If not, see <http://www.gnu.org/copyleft/lesser.html>.
	*/

// init variables
var trcopy;
var editing = 0;
var tdediting = 0;
var editingtrid = 0;
var editingtdcol = 0;
var inputs = ':checked,:selected,:text,textarea,select,file';

$(document).ready(function(){

	// set images for edit and delete 
	$(".eimage").attr("src",editImage);
	$(".dimage").attr("src",deleteImage);

	// init table
	blankrow = '<tr valign="top" class="inputform"><td></td>';
	for(i=0;i<columns.length;i++){
		// Create input element as per the definition
		input = createInput(i,'');
		blankrow += '<td class="ajaxReq">'+input+'</td>';
	}
	blankrow += '<td colspan="2"><a href="javascript:;" class="'+savebutton+'"><img src="'+saveImage+'"></a></td></tr>';
	
	// append blank row at the first/end of table
	$("."+table).prepend(blankrow); // append
	
	// Delete record
	$(document).on("click","."+deletebutton,function(){
		var id = $(this).attr("id");
		if(id){
			if(confirm("Do you really want to delete record ?"))
				ajax("rid="+id,"del");
		}
	});

	// Add new record
	$("."+savebutton).on("click",function(){
		var validation = 1;

		var $inputs =
		$(document).find("."+table).find(inputs).filter(function() {
			// check if input element is blank ??
			/*
            if($.trim( this.value ) == ""){
				$(this).addClass("error");
				validation = 0;
			}else{
				$(this).addClass("success");
			}
            */

            //console.log(this.value);
            if($.trim( this.value ) == ""){
				this.value= "-";
			}
            
            $(this).addClass("success");
			return $.trim( this.value );
		});


		var array = $inputs.map(function(){
			//console.log(this.value);
			//console.log(this);
			return this.value;
		}).get();
		
		var serialized = $inputs.serialize();
		if(validation == 1){
			ajax(serialized,"save");
		}
	});

	// Edit a record
	$(document).on("click","."+editbutton,function(){
		var id = $(this).attr("id");
		if(id && editing == 0 && tdediting == 0){
			// hide editing row, for the time being
            //$("."+table+" tr:last-child").fadeOut("fast");
			$("."+table+" tbody tr:first-child").fadeOut("fast");
						
			var html;
			html += "<td>"+$("."+table+" tr[id="+id+"] td:first-child").html()+"</td>";
			for(i=0;i<columns.length;i++){
				// fetch value inside the TD and place as VALUE in input field
				var val = $(document).find("."+table+" tr[id="+id+"] td[class='"+columns[i]+"']").html();
				input = createInput(i,val);
				html +='<td>'+input+'</td>';
			}
			html += '<td><a href="javascript:;" id="'+id+'" class="'+updatebutton+'"><img src="'+updateImage+'"></a> <a href="javascript:;" id="'+id+'" class="'+cancelbutton+'"><img src="'+cancelImage+'"></a></td>';
			
			// Before replacing the TR contents, make a copy so when user clicks on 
			trcopy = $("."+table+" tr[id="+id+"]").html();
			$("."+table+" tr[id="+id+"]").html(html);	
			
			// set editing flag
			editing = 1;
		}
	});

	$(document).on("click","."+cancelbutton,function(){
		var id = $(this).attr("id");
		$("."+table+" tr[id='"+id+"']").html(trcopy);
		//$("."+table+" tr:last-child").fadeIn("fast");
        $("."+table+" tr:first-child").fadeIn("fast");
		editing = 0;
	});
	
	// Save button click on complete row update event
	$(document).on("click","."+updatebutton,function(){
		id = $(this).attr("id");
		serialized = $("."+table+" tr[id='"+id+"']").find(inputs).serialize();
		ajax(serialized+"&rid="+id,"update");
		return;
		// clear editing flag
		editing = 0;
	});

	// td doubleclick event
	$(document).on("dblclick","."+table+" td",function(e){
		// check if any other TD is in editing mode ? If so then dont show editing box
		//alert(tdediting+"==="+editing);
		var isEditingform = $(this).closest("tr").attr("class");
		if(tdediting == 0 && editing == 0 && isEditingform != "inputform"){
			editingtrid = $(this).closest('tr').attr("id");
			editingtdcol = $(this).attr("class");
			/// If class name (column) is not set, that means this td/column is not supposed to be editable
			if(editingtdcol != undefined){
				var text = $(this).html();
				var tr = $(this).parent();
				var tbody = tr.parent();
				for (var i = 0; i < tr.children().length; i++) {
					if (tr.children().get(i) == this) {
						var column = i;
						break;
					}
				}
			
				// decrement column value by one to avoid sr no column
				column--; 
				//alert(column+"==="+placeholder[column]);
				if(column <= columns.length){
					var text = $(this).html();
					//alert(text);
					input = createInput(column,text);
					$(this).html(input);
					$(this).find(inputs).focus();
					tdediting = 1;
				}
			}
		}
	});
	
	// td lost focus event
	
	$(document).on("blur","."+table+" td",function(e){
		if(tdediting == 1){
			var newval = $("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").find(inputs).val();
			ajax(editingtdcol+"="+newval+"&rid="+editingtrid,"updatetd");
		}
	});
	
});

createInput = function(i,str){
	str = typeof str !== 'undefined' ? str : null;
	//alert(str);
    
    var inpTypeArr = inputType[i].split('_');    
    
    var newInputType = inpTypeArr[0];
    //alert(newInputType);
      
	if(newInputType == "text"){
		input = '<input style="width:90%; height:100%" type='+newInputType+' name='+columns[i]+' placeholder="'+placeholder[i]+'" value='+str+' >';
	}else if(inputType[i] == "textarea"){
		input = '<textarea style="width:90%; height:100%" name='+columns[i]+' placeholder="'+placeholder[i]+'">'+str+'</textarea>';
	}
	else if(newInputType == "select"){
	   var newSelectOpt =selectOpt[inpTypeArr[1]] ;
	   
		input = '<select name='+columns[i]+'>';
		for(i=0;i<newSelectOpt.length;i++){
			selected = "";
			if(str == newSelectOpt[i])
				selected = "selected";
			input += '<option value="'+newSelectOpt[i]+'" '+selected+'>'+newSelectOpt[i]+'</option>';
		}
		input += '</select>';
		//console.log(str);
	}else if(newInputType == "file"){
		input = '<input type="file" style="width:90%; height:100%" name='+columns[i]+' placeholder="'+placeholder[i]+'" >';
	}
	return input;
}

