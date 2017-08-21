$(document).ready(function(){
	$("#left_div_tj a").click(function(){
		var type = this.innerHTML;
		$.ajax({
    			url:'tongji_shuju.php',
	    		type:'POST',
    			dataType:'json',
    			data:{"type":type},
	    		success:function(data){
				$.each(data, function(n1,value1){
					$("#" + n1).empty();
					var biao = $("<table>");
					biao.appendTo($("#" + n1));
					$.each(value1, function(n,value){
						var tr = $("<tr></tr>");
						tr.appendTo(biao);
						var td = $("<td>" + value.count + "</td>" + "<td>" + value.zuo_zhe + "</td>");
						td.appendTo(tr);
					});
					$("#" + n1).append("</table>");
				});
		   	},
       	 		error:function(xml){
            			alert("shibei!。。。。" + xml.responseText);
        		}
        	});
	});
});