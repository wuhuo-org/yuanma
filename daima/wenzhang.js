$(document).ready(function(){
	var type=$("#right_div_nr").attr("class");
	if(type == 'no'){
		$("#right_div_nr").hide();
		return;
	}
	$.ajax({
    		url:'wenzhang_hanshu.php',
    		type:'GET',
    		dataType:'json',
    		data:{"type":type},
    		success:function(data){
			$.each(data, function(n1,value1){
				var tr = $("<tr></tr>");
				$.each(value1, function(n,value){
					var td = $("<td>" + value + "</td>");
					td.appendTo(tr);
				});
				var td = $("<td><a href = 'hanshu.php?name="+n1+"'>" + n1 + "</a></td>");
				td.appendTo(tr);
				$("#xianshi_tb tbody").append(tr);	
			});
		},
        	error:function(xml){
         		alert("shibei!。。。。" + xml.responseText);
        	}
	});
});