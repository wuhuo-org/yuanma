/*-----------------------------------------------------------*/
/* 函数类：Hanshu*/
/*-----------------------------------------------------------*/
function Hanshu(id,num,shj){
	this.id = parseInt(id);
	this.num_dm = parseInt(num);
	this.shijianchuo = shj;
	this.dm_arr = new Array();
}

Hanshu.prototype.dq_div = 1;
Hanshu.prototype.dq_div_old = 1;
Hanshu.prototype.dq_tjid = '';

Hanshu.prototype.jb_dq_dm = -1;		//代码的局部序号，从0开始
Hanshu.prototype.qj_dq_dm = -1;        	//代码的全局id，dai_ma数据表中的id
Hanshu.prototype.jb_dq_zhsh = -1;	//注释的局部序号，从0开始
Hanshu.prototype.qj_dq_zhsh = -1;    	//注释的全局id，zhi_shi数据表中的id
Hanshu.prototype.jb_dq_fd = -1;		//反对注释的局部序号，从0开始
Hanshu.prototype.qj_dq_fd = -1;           	//反对注释的全局id，fan_dui数据表中的id
Hanshu.prototype.jb_dq_bch = -1;	
Hanshu.prototype.qj_dq_bch = -1; 
Hanshu.prototype.jb_dq_wt = -1;
Hanshu.prototype.qj_dq_wt = -1;  
Hanshu.prototype.jb_dq_hd = -1;	
Hanshu.prototype.qj_dq_hd = -1;   
Hanshu.prototype.jb_dq_fd_hd = -1;	
Hanshu.prototype.qj_dq_fd_hd = -1;    
Hanshu.prototype.jb_dq_bch_hd = -1;	
Hanshu.prototype.qj_dq_bch_hd = -1; 

Hanshu.prototype.dm_div = document.getElementById("right_div");
Hanshu.prototype.zhsh_div = document.getElementById("zhushi_div");
Hanshu.prototype.fd_div = document.getElementById("fandui_div");
Hanshu.prototype.bch_div = document.getElementById("buchong_div");
Hanshu.prototype.wt_div = document.getElementById("wenti_div");
Hanshu.prototype.hd_div = document.getElementById("huida_div");
Hanshu.prototype.fd_div_hd = document.getElementById("fandui_div_hd");
Hanshu.prototype.bch_div_hd = document.getElementById("buchong_div_hd");

//对未处理的条目进行高亮
Hanshu.prototype.gaoLiang = function(){
	for(var i in this.dm_arr){
		this.dm_arr[i].gao_liang = 0;     		
		for(var j in this.dm_arr[i].zhu_shi){			//注释
			this.dm_arr[i].zhu_shi[j].gao_liang = 0;     
			if(this.dm_arr[i].zhu_shi[j].chu_li){
				this.dm_arr[i].zhu_shi[j].gao_liang++;
			}
			for(var k in this.dm_arr[i].zhu_shi[j].bu_chong){        //注释的补充
				if(this.dm_arr[i].zhu_shi[j].bu_chong[k].chu_li){  //若注释有补充需要处理，则先高亮，然后将注释的高亮加1
					this.dm_arr[i].zhu_shi[j].bu_chong[k].p.className = "gl_zhsh";
					this.dm_arr[i].zhu_shi[j].gao_liang++;
				}
				else{
					this.dm_arr[i].zhu_shi[j].bu_chong[k].p.className = "zhushi";
				}
			}
			for(var k in this.dm_arr[i].zhu_shi[j].fan_dui){        //注释的反对
				if(this.dm_arr[i].zhu_shi[j].fan_dui[k].chu_li){
					this.dm_arr[i].zhu_shi[j].fan_dui[k].p.className = "gl_zhsh";
					this.dm_arr[i].zhu_shi[j].gao_liang++;
               		}
				else{
					this.dm_arr[i].zhu_shi[j].fan_dui[k].p.className = "zhushi";
				}			}
			if(this.dm_arr[i].zhu_shi[j].gao_liang){             //若注释需要高亮，则将注释对应的代码高亮加1
				this.dm_arr[i].zhu_shi[j].p.className = "gl_zhsh";
				this.dm_arr[i].gao_liang++;
			}
			else{
				this.dm_arr[i].zhu_shi[j].p.className = "zhushi";
			}
		}
		for(var j in this.dm_arr[i].wen_ti){			//问题
			this.dm_arr[i].wen_ti[j].gao_liang = 0;
			if(this.dm_arr[i].wen_ti[j].chu_li){
				this.dm_arr[i].wen_ti[j].gao_liang++;
			}
			for(var k in this.dm_arr[i].wen_ti[j].hui_da){
				this.dm_arr[i].wen_ti[j].hui_da[k].gao_liang = 0;
				if(this.dm_arr[i].wen_ti[j].hui_da[k].chu_li){
					this.dm_arr[i].wen_ti[j].hui_da[k].gao_liang++;
				}
				for(var h in this.dm_arr[i].wen_ti[j].hui_da[k].bu_chong){
					if(this.dm_arr[i].wen_ti[j].hui_da[k].bu_chong[h].chu_li){
						this.dm_arr[i].wen_ti[j].hui_da[k].bu_chong[h].p.className = "gl_zhsh";
						this.dm_arr[i].wen_ti[j].hui_da[k].gao_liang++;
					}
					else{
						this.dm_arr[i].wen_ti[j].hui_da[k].bu_chong[h].p.className = "zhushi";
					}
				}
				for(var h in this.dm_arr[i].wen_ti[j].hui_da[k].fan_dui){
					if(this.dm_arr[i].wen_ti[j].hui_da[k].fan_dui[h].chu_li){
						this.dm_arr[i].wen_ti[j].hui_da[k].fan_dui[h].p.className = "gl_zhsh";
						this.dm_arr[i].wen_ti[j].hui_da[k].gao_liang++;
					}
					else{
						this.dm_arr[i].wen_ti[j].hui_da[k].fan_dui[h].p.className = "zhushi";
					}
				}
				if(this.dm_arr[i].wen_ti[j].hui_da[k].gao_liang){
					this.dm_arr[i].wen_ti[j].hui_da[k].p.className = "gl_zhsh";
					this.dm_arr[i].wen_ti[j].gao_liang++;
				}
				else{
					this.dm_arr[i].wen_ti[j].hui_da[k].p.className = "zhushi";
				}
			}
			if(this.dm_arr[i].wen_ti[j].gao_liang){
				this.dm_arr[i].wen_ti[j].p.className = "gl_zhsh";
				this.dm_arr[i].gao_liang++;
			}
			else{
				this.dm_arr[i].wen_ti[j].p.className = "zhushi";
			}
		}
		if(this.dm_arr[i].gao_liang){
			this.dm_arr[i].pre.className = "gl_dm";
		}
		else{
			this.dm_arr[i].pre.className = "daima";
		}
	}	
}

Hanshu.prototype.jiaZai_anniu_shijian = function(){
		$("#huida_nr").click(function(){
			if(han_shu.dq_div != 4)
				return;
			han_shu.qj_dq_zhsh = -1;
			han_shu.jb_dq_zhsh = -1;
			han_shu.dq_div = 3;
			$("#huida_left_div").show();
			$("#huida_lsh").hide();
			$("#xia_left_left_div").hide();
			$("#fandui_div").empty();
			$("#xia_left_right_div").hide();
			$("#buchong_div").empty();
			$(this).css("background-color","");

			$("#zhch_wt").attr('disabled',false);
			$("#hd_wt").attr('disabled', false);
		});

		$("#wenti_nr").click(function(){
			if(han_shu.dq_div != 3)
				return;
			han_shu.qj_dq_zhsh = -1;
			han_shu.jb_dq_zhsh = -1;
			han_shu.dq_div = 2;
			$("#left_left_div").show();
			$("#left_right_div").show();
			$("#chuangjian").show();
			$("#wenti_lsh").hide();
			$("#huida_left_div").hide();
			$("#huida_div").empty();
			$(this).css("background-color","");
		});

		$("#zhushi_nr").click(function(){
			if(han_shu.dq_div != 3)
				return;
			han_shu.qj_dq_zhsh = -1;
			han_shu.jb_dq_zhsh = -1;
			han_shu.dq_div = 2;
			$("#left_left_div").show();
			$("#left_right_div").show();
			$("#chuangjian").show();
			$("#zhushi_lsh").hide();
			$("#xia_left_left_div").hide();
			$("#fandui_div").empty();
			$("#xia_left_right_div").hide();
			$("#buchong_div").empty();
			$(this).css("background-color","");
		});
		$("#fandui_hd_nr").click(function(){
			if(han_shu.dq_div != 5)
				return;
			han_shu.qj_dq_fd = -1;
			han_shu.jb_dq_fd = -1;
			han_shu.dq_div = 4;
			$("#xia_left_left_div").show();
			$("#xia_left_right_div").show();
			$("#fandui_hd_lsh").hide();
			$(this).css("background-color","");
			$("#zch_hd").attr('disabled',false);
			$("#bch_hd").attr('disabled', false);
			$("#fd_hd").attr('disabled', false);
		});
		$("#buchong_hd_nr").click(function(){
			if(han_shu.dq_div != 5)
				return;
			han_shu.qj_dq_fd = -1;
			han_shu.jb_dq_fd = -1;
			han_shu.dq_div = 4;
			$("#xia_left_left_div").show();
			$("#xia_left_right_div").show();
			$("#buchong_hd_lsh").hide();
			$(this).css("background-color","");
			$("#zch_hd").attr('disabled',false);
			$("#bch_hd").attr('disabled', false);
			$("#fd_hd").attr('disabled', false);
		});

		$("#fandui_nr").click(function(){
			if(han_shu.dq_div != 4)
				return;
			han_shu.qj_dq_fd = -1;
			han_shu.jb_dq_fd = -1;
			han_shu.dq_div = 3;
			$("#xia_left_left_div").show();
			$("#xia_left_right_div").show();
			$("#fandui_lsh").hide();
			$(this).css("background-color","");
			$("#zch_zhsh").attr('disabled',false);
			$("#bch_zhsh").attr('disabled', false);
			$("#fd_zhsh").attr('disabled', false);
		});
		$("#buchong_nr").click(function(){
			if(han_shu.dq_div != 4)
				return;
			han_shu.qj_dq_bch = -1;
			han_shu.jb_dq_bch = -1;
			han_shu.dq_div = 3;
			$("#xia_left_left_div").show();
			$("#xia_left_right_div").show();
			$("#buchong_lsh").hide();
			$(this).css("background-color","");
			$("#zch_zhsh").attr('disabled',false);
			$("#bch_zhsh").attr('disabled', false);
			$("#fd_zhsh").attr('disabled', false);
		});

//创建、提交
	$("#chj_zhsh").click(this.xianShi_tijao);
	$("#chj_wt").click(this.xianShi_tijao);

	$("#tj_but").click(this.tiJiao_xinxi);
	$("#tj_but_qx").click(this.tiJiao_xinxi_qx);

//补充、反对、回答
	$("#bch_hd").click(this.xianShi_tijao);
	$("#fd_hd").click(this.xianShi_tijao);

	$("#bch_zhsh").click(this.xianShi_tijao);
	$("#fd_zhsh").click(this.xianShi_tijao);
	$("#hd_wt").click(this.xianShi_tijao);

//赞成、反对、支持
	$("#zhch_wt").click(this.anniu_shijian);
	$("#zch_zhsh").click(this.anniu_shijian);
	$("#zch_fd_zhsh").click(this.anniu_shijian);
	$("#fd_fd_zhsh").click(this.anniu_shijian);
	$("#zch_bch_zhsh").click(this.anniu_shijian);
	$("#fd_bch_zhsh").click(this.anniu_shijian);

	$("#zch_hd").click(this.anniu_shijian);
	$("#zch_fd_hd").click(this.anniu_shijian);
	$("#fd_fd_hd").click(this.anniu_shijian);
	$("#zch_bch_hd").click(this.anniu_shijian);
	$("#fd_bch_hd").click(this.anniu_shijian);
}

Hanshu.prototype.anniu_shijian = function(){
	if(han_shu.dq_div == 100){
		return;
	}
	var but_id = this.getAttribute("id");
	if((but_id == 'zch_zhsh' && !han_shu.dm_arr[han_shu.jb_dq_dm].zhu_shi[han_shu.jb_dq_zhsh].chu_li)
	|| (but_id == 'zhch_wt' && !han_shu.dm_arr[han_shu.jb_dq_dm].wen_ti[han_shu.jb_dq_wt].chu_li)
	|| (but_id == 'zch_hd' && !han_shu.dm_arr[han_shu.jb_dq_dm].wen_ti[han_shu.jb_dq_wt].hui_da[han_shu.jb_dq_hd].chu_li)
	|| ((but_id == 'zch_fd_zhsh' || but_id == 'fd_fd_zhsh') && !han_shu.dm_arr[han_shu.jb_dq_dm].zhu_shi[han_shu.jb_dq_zhsh].fan_dui[han_shu.jb_dq_fd].chu_li)
	|| ((but_id == 'zch_bch_zhsh' || but_id == 'fd_bch_zhsh') && !han_shu.dm_arr[han_shu.jb_dq_dm].zhu_shi[han_shu.jb_dq_zhsh].bu_chong[han_shu.jb_dq_bch].chu_li)
	|| ((but_id == 'zch_fd_hd' || but_id == 'fd_fd_hd') && !han_shu.dm_arr[han_shu.jb_dq_dm].wen_ti[han_shu.jb_dq_wt].hui_da[han_shu.jb_dq_hd].fan_dui[han_shu.jb_dq_fd_hd].chu_li)
	|| ((but_id == 'zch_bch_hd' || but_id == 'fd_bch_hd') && !han_shu.dm_arr[han_shu.jb_dq_dm].wen_ti[han_shu.jb_dq_wt].hui_da[han_shu.jb_dq_hd].bu_chong[han_shu.jb_dq_bch_hd].chu_li)){
		alert("请勿重复处理！");
		return;
	}
	startXHR(but_id);
}

Hanshu.prototype.xianShi_tijao = function(){
	han_shu.dq_tjid = this.getAttribute("id");
	if(han_shu.dq_div == 100){
		return;
	}
	han_shu.dq_div_old = han_shu.dq_div;
	han_shu.dq_div = 100;
	if(han_shu.dq_tjid == 'chj_zhsh' || han_shu.dq_tjid == 'chj_wt'){
		if(han_shu.dq_tjid == 'chj_zhsh')
			$("#left_right_div").hide();
		else	
			$("#left_left_div").hide();
		$("#left_mid_div").show();
		$("#tj_div").appendTo($("#left_mid_div"));
		$("#tj_div").show();
	}
	else if(han_shu.dq_tjid == 'bch_zhsh' || han_shu.dq_tjid == 'fd_zhsh'||han_shu.dq_tjid == 'bch_hd' || han_shu.dq_tjid == 'fd_hd'){
		if(han_shu.dq_tjid == 'bch_zhsh'||han_shu.dq_tjid == 'bch_hd' )
			$("#xia_left_right_div").hide();
		else	
			$("#xia_left_left_div").hide();
		$("#xia_left_mid_div").show();
		$("#tj_div").appendTo($("#xia_left_mid_div"));
		$("#tj_div").show();
	}
	else if(han_shu.dq_tjid == 'hd_wt'){
		$("#huida_right_div").show();
		$("#tj_div").appendTo($("#huida_right_div"));
		$("#tj_div").show();
	}
}

Hanshu.prototype.yinCang_tijao = function(){
	this.dq_div = this.dq_div_old;
	if(han_shu.dq_tjid == 'chj_zhsh' || han_shu.dq_tjid == 'chj_wt'){
		$("#left_mid_div").hide();
		$("#left_left_div").show();
		$("#left_right_div").show();
	}
	else if(han_shu.dq_tjid == 'bch_zhsh' || han_shu.dq_tjid == 'fd_zhsh'||han_shu.dq_tjid == 'bch_hd' || han_shu.dq_tjid == 'fd_hd'){
		$("#xia_left_mid_div").hide();
		$("#xia_left_left_div").show();
		$("#xia_left_right_div").show();
	}
	else if(han_shu.dq_tjid == 'hd_wt'){
		$("#huida_right_div").hide();
	}
	$("#tj_nr").val("");
	this.dq_tjid = '';
}

Hanshu.prototype.tiJiao_xinxi_qx = function(){
	han_shu.yinCang_tijao();
	return;
}

Hanshu.prototype.tiJiao_xinxi = function(){
	if(!$("#tj_nr").val()){     //若没有输入，则结束创建问题
		alert("empty!");
	}
	else{
		startXHR(han_shu.dq_tjid);
	}
	han_shu.yinCang_tijao();
	return;
}

Hanshu.prototype.xianShi_daima = function(){
	for(var i in this.dm_arr){
		this.dm_arr[i].xianShi();
	}	
}

Hanshu.prototype.xianShi_xx = function(zz, shj, a1, a2, b1, b2, c1,c2){
	$("#xinxi").show();
	$("#xinxi span:eq(0)").html(a1+a2);
	$("#xinxi span:eq(1)").html(b1+b2);
	if(c1)
		$("#xinxi span:eq(2)").html(c1+c2);
	else
		$("#xinxi span:eq(2)").empty();
	$("#xinxi span:eq(3)").html(shj + '&nbsp&nbsp&nbsp&nbsp' + zz);
}

Hanshu.prototype.yinCang_zz_shj = function(){
	this.mz_rq_div.firstChild.nodeValue = '';
}
/*----------------------------------*/
/* 代码类：Daima*/
/*----------------------------------*/
function Daima(id, xu_hao, nei_rong, zhu_shi, wen_ti){
	this.id = parseInt(id);         //全局id，必须将id转换为int型
	this.xu_hao = parseInt(xu_hao);   //局部id，函数内代码的序号，从0开始
	this.nei_rong = nei_rong;
	this.num_zhsh = parseInt(zhu_shi);
	this.num_wt = parseInt(wen_ti);
	this.pre = document.createElement('pre');
	this.zhu_shi = new Array();
	this.wen_ti = new Array();
	this.gao_liang = 0;
}

/*---1、初始化代码---*/
Daima.prototype.chuShiHua = function(){
	this.pre.appendChild(document.createTextNode(this.nei_rong));//创建代码
	this.jiaZai_shijian();
}

/*---2、显示代码---*/
Daima.prototype.xianShi = function(){
	han_shu.dm_div.appendChild(this.pre);//显示代码
}
	
/*---3、显示、隐藏、锁定：代码对应的注释&问题---*/
Daima.prototype.xianShi_zhshwt = function(){
	if(han_shu.dq_div != 1){
		return;
	}
	this.pre.style.backgroundColor = "#AAAAAA";
	for(var i in this.zhu_shi){		//for in自动判断在有注释的情况下显示注释
		this.zhu_shi[i].xianShi();
	}
	for(var i in this.wen_ti){		//for in自动判断在有注释的情况下显示注释
		this.wen_ti[i].xianShi();
	}
	$("#left_left_div").show();
	$("#left_right_div").show();
}

Daima.prototype.yinCang_zhshwt = function(){
	if(han_shu.dq_div != 1){
		return;
	}
	this.pre.style.backgroundColor = "#dddddd";
	$("#zhushi_div").empty();
	$("#wenti_div").empty();
	$("#left_left_div").hide();
	$("#left_right_div").hide();
}

Daima.prototype.suoDing_jieSuo_zhshwt = function(){
	if(han_shu.dq_div == 1){
		han_shu.dq_div = 2;//进入第二层
		han_shu.jb_dq_dm = this.xu_hao;  //函数内代码的序号，从0开始；用于dm_arr数组的下标
		han_shu.qj_dq_dm = this.id;    //代码在dai_ma数据表中的全局id；用于指定注释和问题的代码号
		$("#chuangjian").show();
	}
	else if(han_shu.dq_div == 2 && han_shu.qj_dq_dm == this.id){
		han_shu.dq_div = 1;
		han_shu.jb_dq_dm = -1;
		han_shu.qj_dq_dm = -1;
		$("#chuangjian").hide();
	}
}

/*---4、加载、卸载：代码事件、代码对应的注释&问题事件---*/
Daima.prototype.jiaZai_shijian = function(){
	this.pre.onmouseover = this.xianShi_zhshwt.bind(this);
	this.pre.onmousedown = this.suoDing_jieSuo_zhshwt.bind(this);
	this.pre.onmouseleave = this.yinCang_zhshwt.bind(this);
}

/*-----------------------------------------------------------------------------*/
/* 注释类：Zhushi*/
/*-----------------------------------------------------------------------------*/
function Zhushi(id, xu_hao, nei_rong, dai_ma, zuo_zhe, shi_jian, chl, zan_cheng, fan_dui, bu_chong){
	this.id = parseInt(id);         //全局id，必须将id转换为int型
	this.xu_hao = parseInt(xu_hao);   //局部id，函数内代码的序号，从0开始
	this.nei_rong = nei_rong;
	this.dai_ma = parseInt(dai_ma);
	this.num_zch = parseInt(zan_cheng);
	this.num_bch = parseInt(bu_chong);
	this.num_fd = parseInt(fan_dui);
	this.bu_chong = new Array();
	this.fan_dui = new Array();
	this.chu_li = parseInt(chl);
	this.zuo_zhe = zuo_zhe;
	this.shi_jian = shi_jian;
	this.gao_liang = 0;
	this.p = document.createElement('p');
}

/*---1、初始化注释---*/
Zhushi.prototype.chuShiHua = function(){
	this.p.appendChild(document.createTextNode(this.nei_rong));
	this.jiaZai_shijian();
}

/*---2、显示注释---*/
Zhushi.prototype.xianShi = function(){
	han_shu.zhsh_div.appendChild(this.p);
}

/*---3、显示、隐藏、锁定/解锁：注释信息---*/
Zhushi.prototype.xianShi_zhushixinxi = function(){
	if(han_shu.dq_div != 2){
		return;
	}
	this.p.style.backgroundColor = "#AAAAAA";
	han_shu.xianShi_xx(this.zuo_zhe, this.shi_jian, "赞成：",this.num_zch, "补充：", this.num_bch, "反对：",  this.num_fd);
}

Zhushi.prototype.yinCang_zhushixinxi = function(){
	if(han_shu.dq_div != 2){
		return;
	}
	this.p.style.backgroundColor = "#dddddd";
	$("#xinxi").hide();
}

Zhushi.prototype.suoDing_jieSuo_zhushixinxi = function(){
	if(han_shu.dq_div == 2){    //锁定
		han_shu.qj_dq_zhsh = this.id;
		han_shu.jb_dq_zhsh = this.xu_hao;
		han_shu.dq_div = 3;

		this.p.style.backgroundColor = "#dddddd";
		$("#left_left_div").hide();
		$("#left_right_div").hide();
		$("#xinxi").hide();
		$("#chuangjian").hide();
		$("#zhushi_lsh").show();
		$("#xia_left_left_div").show();
		$("#xia_left_right_div").show();
		for(var i in this.fan_dui){		//for in自动判断在有注释的情况下显示注释
			this.fan_dui[i].xianShi();
		}
		for(var i in this.bu_chong){		//for in自动判断在有注释的情况下显示注释
			this.bu_chong[i].xianShi();
		}

		$("#zhushi_nr").html(this.nei_rong);
		$("#zhushi_xx span:eq(0)").html(this.num_zch);
		$("#zhushi_xx span:eq(1)").html(this.num_bch);
		$("#zhushi_xx span:eq(2)").html(this.num_fd);
		$("#zhushi_xx span:eq(3)").html(this.shi_jian + '&nbsp&nbsp&nbsp&nbsp' + this.zuo_zhe);

		$("#buchong_div").css('max-height',$("#left_left_div").height()-$("#zhushi_lsh").height()-150);
		$("#fandui_div").css('max-height',$("#left_left_div").height()-$("#zhushi_lsh").height()-150);
	}
}

/*---4、加载、卸载：注释事件---*/
Zhushi.prototype.jiaZai_shijian = function(){
	this.p.onmouseover = this.xianShi_zhushixinxi.bind(this);
	this.p.onmousedown = this.suoDing_jieSuo_zhushixinxi.bind(this);
	this.p.onmouseleave = this.yinCang_zhushixinxi.bind(this);
}

//*-----------------------------------------------------------------------------*/
/* 注释类：Fandui*/
/*-----------------------------------------------------------------------------*/
function Fandui(id, xu_hao, nei_rong, dai_ma, zuo_zhe, shi_jian, chl, zan_cheng, fan_dui, zhu_shi){
	this.id = parseInt(id);         //全局id，必须将id转换为int型
	this.xu_hao = parseInt(xu_hao);   //局部id，函数内代码的序号，从0开始
	this.nei_rong = nei_rong;
	this.dai_ma = parseInt(dai_ma);
	this.num_zch = parseInt(zan_cheng);
	this.num_fd = parseInt(fan_dui);
	this.chu_li = parseInt(chl);
	this.zuo_zhe = zuo_zhe;
	this.shi_jian = shi_jian;	
	this.zhu_shi = zhu_shi;
	this.gao_liang = 0;
	this.p = document.createElement('p');
}

/*---1、初始化反对---*/
Fandui.prototype.chuShiHua = function(){
	this.p.appendChild(document.createTextNode(this.nei_rong));
	this.jiaZai_shijian();
}

/*---2、显示反对---*/
Fandui.prototype.xianShi = function(){
	han_shu.fd_div.appendChild(this.p);
}

/*---3、显示、隐藏、锁定/解锁：反对信息---*/
Fandui.prototype.xianShi_fanduixinxi = function(){
	if(han_shu.dq_div != 3){
		return;
	}
	this.p.style.backgroundColor = "#AAAAAA";
	han_shu.xianShi_xx(this.zuo_zhe, this.shi_jian, "赞成：",this.num_zch, "反对：",  this.num_fd,0,0);
}

Fandui.prototype.yinCang_fanduixinxi = function(){
	if(han_shu.dq_div != 3){
		return;
	}
	this.p.style.backgroundColor = "#dddddd";
	$("#xinxi").hide();
}

Fandui.prototype.suoDing_jieSuo_fanduixinxi = function(){
	if(han_shu.dq_div == 3){   //若在第三层，进入第四层
		han_shu.qj_dq_fd = this.id;
		han_shu.jb_dq_fd = this.xu_hao;
		han_shu.dq_div = 4;

		this.p.style.backgroundColor = "#dddddd";
		$("#xia_left_left_div").hide();
		$("#xia_left_right_div").hide();
		$("#xinxi").hide();
		$("#fandui_lsh").show();

		$("#fandui_nr").html(this.nei_rong);
		$("#fandui_xx span:eq(0)").html(this.num_zch);
		$("#fandui_xx span:eq(1)").html(this.num_fd);
		$("#fandui_xx span:eq(2)").html(this.shi_jian + '&nbsp&nbsp&nbsp&nbsp' + this.zuo_zhe);

		$("#zch_zhsh").attr('disabled', true);
		$("#bch_zhsh").attr('disabled', true);
		$("#fd_zhsh").attr('disabled', true);

		$("#fandui_nr").css('max-height',$("#left_left_div").height()-$("#zhushi_lsh").height()-200);
	}
}

/*---4、加载、卸载：注释事件---*/
Fandui.prototype.jiaZai_shijian = function(){
	this.p.onmouseover = this.xianShi_fanduixinxi.bind(this);
	this.p.onmousedown = this.suoDing_jieSuo_fanduixinxi.bind(this);
	this.p.onmouseleave = this.yinCang_fanduixinxi.bind(this);
}

//*-----------------------------------------------------------------------------*/
/* 注释类：Buchong*/
/*-----------------------------------------------------------------------------*/
function Buchong(id, xu_hao, nei_rong, dai_ma, zuo_zhe, shi_jian, chl, zan_cheng, fan_dui, zhu_shi){
	this.id = parseInt(id);         //全局id，必须将id转换为int型
	this.xu_hao = parseInt(xu_hao);   //局部id，函数内代码的序号，从0开始
	this.nei_rong = nei_rong;
	this.dai_ma = parseInt(dai_ma);
	this.num_zch = parseInt(zan_cheng);
	this.num_fd = parseInt(fan_dui);
	this.chu_li = parseInt(chl);
	this.zuo_zhe = zuo_zhe;
	this.shi_jian = shi_jian;	
	this.zhu_shi = zhu_shi;
	this.gao_liang = 0;
	this.p = document.createElement('p');
}

/*---1、初始化反对---*/
Buchong.prototype.chuShiHua = function(){
	this.p.appendChild(document.createTextNode(this.nei_rong));
	this.jiaZai_shijian();
}

/*---2、显示反对---*/
Buchong.prototype.xianShi = function(){
	han_shu.bch_div.appendChild(this.p);
}

/*---3、显示、隐藏、锁定/解锁：反对信息---*/
Buchong.prototype.xianShi_buchongxinxi = function(){
	if(han_shu.dq_div != 3){
		return;
	}
	this.p.style.backgroundColor = "#AAAAAA";
	han_shu.xianShi_xx(this.zuo_zhe, this.shi_jian, "赞成：",this.num_zch, "反对：",  this.num_fd,0,0);
}

Buchong.prototype.yinCang_buchongxinxi = function(){
	if(han_shu.dq_div != 3){
		return;
	}
	this.p.style.backgroundColor = "#dddddd";
	$("#xinxi").hide();
}

Buchong.prototype.suoDing_jieSuo_buchongxinxi = function(){	
	if(han_shu.dq_div == 3){   //若在第三层，进入第四层
		han_shu.qj_dq_bch = this.id;
		han_shu.jb_dq_bch = this.xu_hao;
		han_shu.dq_div = 4;

		this.p.style.backgroundColor = "#dddddd";
		$("#xia_left_left_div").hide();
		$("#xia_left_right_div").hide();
		$("#xinxi").hide();
		$("#buchong_lsh").show();

		$("#buchong_nr").html(this.nei_rong);
		$("#buchong_xx span:eq(0)").html(this.num_zch);
		$("#buchong_xx span:eq(1)").html(this.num_fd);
		$("#buchong_xx span:eq(2)").html(this.shi_jian + '&nbsp&nbsp&nbsp&nbsp' + this.zuo_zhe);
		$("#zch_zhsh").attr('disabled', true);
		$("#bch_zhsh").attr('disabled', true);
		$("#fd_zhsh").attr('disabled', true);

		$("#buchong_nr").css('max-height',$("#left_left_div").height()-$("#zhushi_lsh").height()-200);
	}
}

/*---4、加载、卸载：注释事件---*/
Buchong.prototype.jiaZai_shijian = function(){
	this.p.onmouseover = this.xianShi_buchongxinxi.bind(this);
	this.p.onmousedown = this.suoDing_jieSuo_buchongxinxi.bind(this);
	this.p.onmouseleave = this.yinCang_buchongxinxi.bind(this);
}
/*-----------------------------------------------------------------------------*/
/* 问题类：Wenti*/
/*-----------------------------------------------------------------------------*/
function Wenti(id, xu_hao, nei_rong, dai_ma, zuo_zhe, shi_jian, chl, zhi_chi, hui_da){
	this.id = parseInt(id);         //全局id，必须将id转换为int型
	this.xu_hao = parseInt(xu_hao);   //局部id，函数内代码的序号，从0开始
	this.nei_rong = nei_rong;
	this.dai_ma = parseInt(dai_ma);
	this.num_zhch = parseInt(zhi_chi);
	this.num_hd = parseInt(hui_da);
	this.chu_li = parseInt(chl);
	this.zuo_zhe = zuo_zhe;
	this.shi_jian = shi_jian;	
	this.gao_liang = 0;
	this.p = document.createElement('p');
	this.hui_da = new Array();
}

/*---1、初始化问题---*/
Wenti.prototype.chuShiHua = function(){
	this.p.appendChild(document.createTextNode(this.nei_rong));
	this.jiaZai_shijian();
}

/*---2、显示问题---*/
Wenti.prototype.xianShi = function(){
	han_shu.wt_div.appendChild(this.p);
}

/*---3、显示、隐藏、锁定/解锁：问题信息---*/
Wenti.prototype.xianShi_wentixinxi = function(){
	if(han_shu.dq_div != 2){
		return;
	}
	this.p.style.backgroundColor = "#AAAAAA";
	han_shu.xianShi_xx(this.zuo_zhe, this.shi_jian, "支持：", this.num_zhch, "回答：", this.num_hd, 0, 0);
}

Wenti.prototype.yinCang_wentixinxi = function(){
	if(han_shu.dq_div != 2){
		return;
	}
	this.p.style.backgroundColor = "#dddddd";
	$("#xinxi").hide();
}

Wenti.prototype.suoDing_jieSuo_wentixinxi = function(){
	if(han_shu.dq_div == 2){    //锁定
		han_shu.qj_dq_wt = this.id;
		han_shu.jb_dq_wt = this.xu_hao;
		han_shu.dq_div = 3;

		for(var i in this.hui_da){		//for in自动判断在有问题的情况下显示问题
			this.hui_da[i].xianShi();
		}
		this.p.style.backgroundColor = "#dddddd";
		$("#left_left_div").hide();
		$("#left_right_div").hide();
		$("#xinxi").hide();
		$("#chuangjian").hide();
		$("#wenti_lsh").show();
		$("#huida_left_div").show();

		$("#wenti_nr").html(this.nei_rong);
		$("#wenti_xx span:eq(0)").html(this.num_zhch);
		$("#wenti_xx span:eq(1)").html(this.num_hd);
		$("#wenti_xx span:eq(2)").html(this.shi_jian + '&nbsp&nbsp&nbsp&nbsp' + this.zuo_zhe);

		$("#huida_div").css('max-height',$("#left_left_div").height()-$("#wenti_lsh").height()-150);
	}
}

/*---4、加载、卸载：问题事件---*/
Wenti.prototype.jiaZai_shijian = function(){
	this.p.onmouseover = this.xianShi_wentixinxi.bind(this);
	this.p.onmousedown = this.suoDing_jieSuo_wentixinxi.bind(this);
	this.p.onmouseleave = this.yinCang_wentixinxi.bind(this);
}

/*-----------------------------------------------------------------------------*/
/* 回答类：Huida*/
/*-----------------------------------------------------------------------------*/
function Huida(id, xu_hao, nei_rong, dai_ma, zuo_zhe, shi_jian, chl, zan_cheng, fan_dui, bu_chong, wen_ti){
	this.id = parseInt(id);         //全局id，必须将id转换为int型
	this.xu_hao = parseInt(xu_hao);   //局部id，函数内代码的序号，从0开始
	this.nei_rong = nei_rong;
	this.dai_ma = parseInt(dai_ma);
	this.num_zch = parseInt(zan_cheng);
	this.num_fd = parseInt(fan_dui);
	this.num_bch = parseInt(bu_chong);
	this.chu_li = parseInt(chl);
	this.zuo_zhe = zuo_zhe;
	this.shi_jian = shi_jian;	
	this.wen_ti = wen_ti;
	this.bu_chong = new Array();
	this.fan_dui = new Array();
	this.gao_liang = 0;
	this.p = document.createElement('p');
}

/*---1、初始化回答---*/
Huida.prototype.chuShiHua = function(){
	this.p.appendChild(document.createTextNode(this.nei_rong));
	this.jiaZai_shijian();
}

/*---2、显示回答---*/
Huida.prototype.xianShi = function(){
	han_shu.hd_div.appendChild(this.p);
}

/*---3、显示、隐藏、锁定/解锁：回答信息---*/
Huida.prototype.xianShi_huidaxinxi = function(){
	if(han_shu.dq_div != 3){
		return;
	}
	this.p.style.backgroundColor = "#AAAAAA";
	han_shu.xianShi_xx(this.zuo_zhe, this.shi_jian, "赞成：",this.num_zch, "补充：", this.num_bch, "反对：",  this.num_fd);
}

Huida.prototype.yinCang_huidaxinxi = function(){
	if(han_shu.dq_div != 3){
		return;
	}
	this.p.style.backgroundColor = "#dddddd";
	$("#xinxi").hide();
}

Huida.prototype.suoDing_jieSuo_huidaxinxi = function(){
	if(han_shu.dq_div == 3){    //锁定
		han_shu.qj_dq_hd = this.id;
		han_shu.jb_dq_hd = this.xu_hao;
		han_shu.dq_div = 4;

		this.p.style.backgroundColor = "#dddddd";
		$("#huida_left_div").hide();
		$("#xinxi").hide();
		$("#huida_lsh").show();
		$("#xia_left_left_div").show();
		$("#xia_left_right_div").show();
		for(var i in this.fan_dui){		//for in自动判断在有回答的情况下显示回答
			this.fan_dui[i].xianShi();
		}
		for(var i in this.bu_chong){		//for in自动判断在有回答的情况下显示回答
			this.bu_chong[i].xianShi();
		}
		$("#huida_nr").html(this.nei_rong);
		$("#huida_xx span:eq(0)").html(this.num_zch);
		$("#huida_xx span:eq(1)").html(this.num_bch);
		$("#huida_xx span:eq(2)").html(this.num_fd);
		$("#huida_xx span:eq(3)").html(this.shi_jian + '&nbsp&nbsp&nbsp&nbsp' + this.zuo_zhe);

		$("#zhch_wt").attr('disabled',true);
		$("#hd_wt").attr('disabled', true);

		$("#buchong_div").css('max-height',$("#left_left_div").height()-$("#huida_lsh").height()-$("#wenti_lsh").height()-160);
		$("#fandui_div").css('max-height',$("#left_left_div").height()-$("#huida_lsh").height()-$("#wenti_lsh").height()-160);
	}
}

/*---4、加载、卸载：回答事件---*/
Huida.prototype.jiaZai_shijian = function(){
	this.p.onmouseover = this.xianShi_huidaxinxi.bind(this);
	this.p.onmousedown = this.suoDing_jieSuo_huidaxinxi.bind(this);
	this.p.onmouseleave = this.yinCang_huidaxinxi.bind(this);
}
//*-----------------------------------------------------------------------------*/
/* 注释类：Buchong_hd*/
/*-----------------------------------------------------------------------------*/
function Buchong_hd(id, xu_hao, nei_rong, dai_ma, zuo_zhe, shi_jian, chl, zan_cheng, fan_dui, wen_ti, hui_da){
	this.id = parseInt(id);         //全局id，必须将id转换为int型
	this.xu_hao = parseInt(xu_hao);   //局部id，函数内代码的序号，从0开始
	this.nei_rong = nei_rong;
	this.dai_ma = parseInt(dai_ma);
	this.num_zch = parseInt(zan_cheng);
	this.num_fd = parseInt(fan_dui);
	this.chu_li = parseInt(chl);
	this.zuo_zhe = zuo_zhe;
	this.shi_jian = shi_jian;	
	this.wen_ti = wen_ti;
	this.hui_da = hui_da;
	this.gao_liang = 0;
	this.p = document.createElement('p');
}

/*---1、初始化反对---*/
Buchong_hd.prototype.chuShiHua = function(){
	this.p.appendChild(document.createTextNode(this.nei_rong));
	this.jiaZai_shijian();
}

/*---2、显示反对---*/
Buchong_hd.prototype.xianShi = function(){
	han_shu.bch_div.appendChild(this.p);
}

/*---3、显示、隐藏、锁定/解锁：反对信息---*/
Buchong_hd.prototype.xianShi_buchongxinxi = function(){
	if(han_shu.dq_div != 4){
		return;
	}
	this.p.style.backgroundColor = "#AAAAAA";
	han_shu.xianShi_xx(this.zuo_zhe, this.shi_jian, "赞成：",this.num_zch, "反对：",  this.num_fd,0,0);
}

Buchong_hd.prototype.yinCang_buchongxinxi = function(){
	if(han_shu.dq_div != 4){
		return;
	}
	this.p.style.backgroundColor = "#dddddd";
	$("#xinxi").hide();
}

Buchong_hd.prototype.suoDing_jieSuo_buchongxinxi = function(){
	if(han_shu.dq_div == 4){   //若在第三层，进入第四层
		han_shu.qj_dq_bch_hd = this.id;
		han_shu.jb_dq_bch_hd = this.xu_hao;
		han_shu.dq_div = 5;

		this.p.style.backgroundColor = "#dddddd";
		$("#xia_left_left_div").hide();
		$("#xia_left_right_div").hide();
		$("#xinxi").hide();
		$("#buchong_hd_lsh").show();

		$("#buchong_hd_nr").html(this.nei_rong);
		$("#buchong_hd_xx span:eq(0)").html(this.num_zch);
		$("#buchong_hd_xx span:eq(1)").html(this.num_fd);
		$("#buchong_hd_xx span:eq(2)").html(this.shi_jian + '&nbsp&nbsp&nbsp&nbsp' + this.zuo_zhe);
		$("#zch_hd").attr('disabled', true);
		$("#bch_hd").attr('disabled', true);
		$("#fd_hd").attr('disabled', true);

		$("#buchong_hd_nr").css('max-height',$("#left_left_div").height()-$("#huida_lsh").height()-$("#wenti_lsh").height()-210);
	}
}

/*---4、加载、卸载：注释事件---*/
Buchong_hd.prototype.jiaZai_shijian = function(){
	this.p.onmouseover = this.xianShi_buchongxinxi.bind(this);
	this.p.onmousedown = this.suoDing_jieSuo_buchongxinxi.bind(this);
	this.p.onmouseleave = this.yinCang_buchongxinxi.bind(this);
}
//*-----------------------------------------------------------------------------*/
/* 反对回答类：Fandui_hd*/
/*-----------------------------------------------------------------------------*/
function Fandui_hd(id, xu_hao, nei_rong, dai_ma, zuo_zhe, shi_jian, chl, zan_cheng, fan_dui, wen_ti, hui_da){
	this.id = parseInt(id);         //全局id，必须将id转换为int型
	this.xu_hao = parseInt(xu_hao);   //局部id，函数内代码的序号，从0开始
	this.nei_rong = nei_rong;
	this.dai_ma = parseInt(dai_ma);
	this.num_zch = parseInt(zan_cheng);
	this.num_fd = parseInt(fan_dui);
	this.chu_li = parseInt(chl);
	this.zuo_zhe = zuo_zhe;
	this.shi_jian = shi_jian;	
	this.wen_ti = wen_ti;
	this.hui_da = hui_da;
	this.gao_liang = 0;
	this.p = document.createElement('p');
}

/*---1、初始化反对---*/
Fandui_hd.prototype.chuShiHua = function(){
	this.p.appendChild(document.createTextNode(this.nei_rong));
	this.jiaZai_shijian();
}

/*---2、显示反对---*/
Fandui_hd.prototype.xianShi = function(){
	han_shu.fd_div.appendChild(this.p);
}

/*---3、显示、隐藏、锁定/解锁：反对信息---*/
Fandui_hd.prototype.xianShi_huidaxinxi = function(){
	if(han_shu.dq_div != 4){
		return;
	}
	this.p.style.backgroundColor = "#AAAAAA";
	han_shu.xianShi_xx(this.zuo_zhe, this.shi_jian, "赞成：",this.num_zch, "反对：",  this.num_fd,0,0);
}

Fandui_hd.prototype.yinCang_huidaxinxi = function(){
	if(han_shu.dq_div != 4){
		return;
	}
	this.p.style.backgroundColor = "#dddddd";
	$("#xinxi").hide();
}

Fandui_hd.prototype.suoDing_jieSuo_huidaxinxi = function(){
	if(han_shu.dq_div == 4){   //若在第三层，进入第四层
		han_shu.qj_dq_fd_hd = this.id;
		han_shu.jb_dq_fd_hd = this.xu_hao;
		han_shu.dq_div = 5;

		this.p.style.backgroundColor = "#dddddd";
		$("#xia_left_left_div").hide();
		$("#xia_left_right_div").hide();
		$("#xinxi").hide();
		$("#fandui_hd_lsh").show();

		$("#fandui_hd_nr").html(this.nei_rong);
		$("#fandui_hd_xx span:eq(0)").html(this.num_zch);
		$("#fandui_hd_xx span:eq(1)").html(this.num_fd);
		$("#fandui_hd_xx span:eq(2)").html(this.shi_jian + '&nbsp&nbsp&nbsp&nbsp' + this.zuo_zhe);
		$("#zch_hd").attr('disabled', true);
		$("#bch_hd").attr('disabled', true);
		$("#fd_hd").attr('disabled', true);

		$("#fandui_hd_nr").css('max-height',$("#left_left_div").height()-$("#huida_lsh").height()-$("#wenti_lsh").height()-210);
	}
}

/*---4、加载、卸载：注释事件---*/
Fandui_hd.prototype.jiaZai_shijian = function(){
	this.p.onmouseover = this.xianShi_huidaxinxi.bind(this);
	this.p.onmousedown = this.suoDing_jieSuo_huidaxinxi.bind(this);
	this.p.onmouseleave = this.yinCang_huidaxinxi.bind(this);
}

function GetJsonData(type) {
	var nei_rong = $("#tj_nr").val(); 
   	var json = {
		"qj_dm": han_shu.qj_dq_dm, 
		"jb_dm": han_shu.jb_dq_dm, 
		"qj_wt": han_shu.qj_dq_wt, 
		"jb_wt": han_shu.jb_dq_wt, 
		"qj_zhsh": han_shu.qj_dq_zhsh, 
		"jb_zhsh": han_shu.jb_dq_zhsh, 
		"qj_hd": han_shu.qj_dq_hd, 
		"jb_hd": han_shu.jb_dq_hd, 
		"qj_fd": han_shu.qj_dq_fd, 
		"qj_bch": han_shu.qj_dq_bch, 
		"qj_fd_hd": han_shu.qj_dq_fd_hd, 
		"qj_bch_hd": han_shu.qj_dq_bch_hd, 
		"txt": nei_rong, 
		"hanshu": han_shu.id, 
		"type": type, 
		"shijianchuo": han_shu.shijianchuo
	};
    	return json;
}
function startXHR(type){
	$.ajax({
    	url:'hanshu_gengxin.php',
    	type:'POST',
    	dataType:'json',
    	data:GetJsonData(type),
    	success:function(data){
			$.each(data, function(n1,value1){
				$.each(value1, function(n,value){
					switch(n1){
						case 'zhsh_wt':
							han_shu.shijianchuo = value.shijian_chuo;
							var tmp = han_shu.dm_arr[value.xu_hao];
							tmp.num_zhsh = value.zhu_shi;
							tmp.num_wt = value.num_wt;
							break;
						case 'zhsh':
							var tmp = han_shu.dm_arr[value.dai_ma].zhu_shi[value.xu_hao];
							if(tmp){
								tmp.num_zch = parseInt(value.zan_cheng);
								tmp.num_bch = parseInt(value.bu_chong);
								tmp.num_fd = parseInt(value.fan_dui);
								tmp.chu_li = parseInt(value.chl);
								if(value.id == han_shu.qj_dq_zhsh){
									$("#zhushi_xx span:eq(0)").html(tmp.num_zch);
									$("#zhushi_xx span:eq(1)").html(tmp.num_bch);
									$("#zhushi_xx span:eq(2)").html(tmp.num_fd);
								}
							}
							else{
								var tmp = new Zhushi(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.bu_chong);
								tmp.chuShiHua();
								if(value.dai_ma == han_shu.jb_dq_dm){
									tmp.xianShi();
								}
								han_shu.dm_arr[value.dai_ma].zhu_shi[value.xu_hao] = tmp;
							}			
							break;
						case 'wt':
							var tmp = han_shu.dm_arr[value.dai_ma].wen_ti[value.xu_hao];
							if(tmp){
								tmp.num_zhch = parseInt(value.zhi_chi);
								tmp.num_hd = parseInt(value.hui_da);
								tmp.chu_li = parseInt(value.chl);
								if(value.id == han_shu.qj_dq_wt){
									$("#wenti_xx span:eq(0)").html(tmp.num_zhch);
									$("#wenti_xx span:eq(1)").html(tmp.num_hd);
								}
							}
							else{
								var tmp = new Wenti(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zhi_chi, value.hui_da);
								tmp.chuShiHua();
								if(value.dai_ma == han_shu.jb_dq_dm){
									tmp.xianShi();
								}
								han_shu.dm_arr[value.dai_ma].wen_ti[value.xu_hao] = tmp;
							}			
							break;
						case 'fd':
							var tmp = han_shu.dm_arr[value.dai_ma].zhu_shi[value.zhu_shi].fan_dui[value.xu_hao]
							if(tmp){
								tmp.num_zch = parseInt(value.zan_cheng);
								tmp.num_fd = parseInt(value.fan_dui);
								tmp.chu_li = parseInt(value.chl);
								if(value.id == han_shu.qj_dq_fd){
									$("#fandui_xx span:eq(0)").html(tmp.num_zch);
									$("#fandui_xx span:eq(1)").html(tmp.num_fd);
								}
							}
							else{
								var tmp = new Fandui(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.zhu_shi);
								tmp.chuShiHua();
								han_shu.dm_arr[value.dai_ma].zhu_shi[value.zhu_shi].fan_dui[value.xu_hao] = tmp;
								if(value.dai_ma == han_shu.jb_dq_dm && value.zhu_shi == han_shu.jb_dq_zhsh){
									tmp.xianShi();
								}
							}			
							break;
						case 'bch':
							var tmp = han_shu.dm_arr[value.dai_ma].zhu_shi[value.zhu_shi].bu_chong[value.xu_hao]
							if(tmp){
								tmp.num_zch = parseInt(value.zan_cheng);
								tmp.num_fd = parseInt(value.fan_dui);
								tmp.chu_li = parseInt(value.chl);
								if(value.id == han_shu.qj_dq_bch){
									$("#buchong_xx span:eq(0)").html(tmp.num_zch);
									$("#buchong_xx span:eq(1)").html(tmp.num_fd);
								}
							}
							else{
								var tmp = new Buchong(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.zhu_shi);
								tmp.chuShiHua();
								han_shu.dm_arr[value.dai_ma].zhu_shi[value.zhu_shi].bu_chong[value.xu_hao] = tmp;
								if(value.dai_ma == han_shu.jb_dq_dm && value.zhu_shi == han_shu.jb_dq_zhsh){
									tmp.xianShi();
								}
							}			
							break;
						case 'hd':
							var tmp = han_shu.dm_arr[value.dai_ma].wen_ti[value.wen_ti].hui_da[value.xu_hao];
							if(tmp){
								tmp.num_zch = parseInt(value.zan_cheng);
								tmp.num_bch = parseInt(value.bu_chong);
								tmp.num_fd = parseInt(value.fan_dui);
								tmp.chu_li = parseInt(value.chl);
								if(value.id == han_shu.qj_dq_hd){
									$("#huida_xx span:eq(0)").html(tmp.num_zch);
									$("#huida_xx span:eq(1)").html(tmp.num_bch);
									$("#huida_xx span:eq(2)").html(tmp.num_fd);
								}
							}
							else{
								var tmp = new Huida(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.bu_chong, value.wen_ti);
								tmp.chuShiHua();
								han_shu.dm_arr[value.dai_ma].wen_ti[value.wen_ti].hui_da[value.xu_hao] = tmp;
								if(value.dai_ma == han_shu.jb_dq_dm && value.wen_ti == han_shu.jb_dq_wt){
									tmp.xianShi();
								}
							}			
							break;
						case 'bch_hd':
							var tmp = han_shu.dm_arr[value.dai_ma].wen_ti[value.wen_ti].hui_da[value.hui_da].bu_chong[value.xu_hao]
							if(tmp){
								tmp.num_zch = parseInt(value.zan_cheng);
								tmp.num_fd = parseInt(value.fan_dui);
								tmp.chu_li = parseInt(value.chl);
								if(value.id == han_shu.qj_dq_bch_hd){
									$("#buchong_hd_xx span:eq(0)").html(tmp.num_zch);
									$("#buchong_hd_xx span:eq(1)").html(tmp.num_fd);
								}
							}
							else{
								var tmp = new Buchong_hd(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.wen_ti, value.hui_da);
								tmp.chuShiHua();
								han_shu.dm_arr[value.dai_ma].wen_ti[value.wen_ti].hui_da[value.hui_da].bu_chong[value.xu_hao] = tmp;
								if(value.dai_ma == han_shu.jb_dq_dm && value.wen_ti == han_shu.jb_dq_wt && value.hui_da == han_shu.jb_dq_hd){
									tmp.xianShi();
								}
							}			
							break;
						case 'fd_hd':
							var tmp = han_shu.dm_arr[value.dai_ma].wen_ti[value.wen_ti].hui_da[value.hui_da].fan_dui[value.xu_hao]
							if(tmp){
								tmp.num_zch = parseInt(value.zan_cheng);
								tmp.num_fd = parseInt(value.fan_dui);
								tmp.chu_li = parseInt(value.chl);
								if(value.id == han_shu.qj_dq_fd_hd){
									$("#fandui_hd_xx span:eq(0)").html(tmp.num_zch);
									$("#fandui_hd_xx span:eq(1)").html(tmp.num_fd);
								}
							}
							else{
								var tmp = new Fandui_hd(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.wen_ti, value.hui_da);
								tmp.chuShiHua();
								han_shu.dm_arr[value.dai_ma].wen_ti[value.wen_ti].hui_da[value.hui_da].fan_dui[value.xu_hao] = tmp;
								if(value.dai_ma == han_shu.jb_dq_dm && value.wen_ti == han_shu.jb_dq_wt && value.hui_da == han_shu.jb_dq_hd){
									tmp.xianShi();
								}
							}			
							break;
					}  
             	});
			});
			han_shu.gaoLiang();
   		},
        error:function(xml){
            alert("shibei!。。。。" + xml.responseText);
        }
   });
}

$(document).ready(function(){
	var name=$("#mingzi").text();
	$.ajax({
    	url:'hanshu_chuangjian.php',
    	type:'POST',
    	dataType:'json',
    	data:{"name":name},
    	success:function(data){
			$.each(data, function(n1,value1){
				$.each(value1, function(n,value){
					switch(n1){
						case 'han_shu':
							han_shu = new Hanshu(value.id_hanshu,value.num_daima,value.shijian_chuo);
							break;
						case 'dai_ma':
							var tmp = new Daima(value.id, value.xu_hao, value.nei_rong, value.zhu_shi, value.wen_ti);
							tmp.chuShiHua();
							han_shu.dm_arr[value.xu_hao] = tmp;
							break;
						case 'zhu_shi':
							var tmp = new Zhushi(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.bu_chong);
							tmp.chuShiHua();
							han_shu.dm_arr[value.dai_ma].zhu_shi[value.xu_hao] = tmp;
							break;
						case 'wen_ti':
							var tmp = new Wenti(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zhi_chi, value.hui_da);
							tmp.chuShiHua();
							han_shu.dm_arr[value.dai_ma].wen_ti[value.xu_hao] = tmp;
							break;
						case 'hui_da':
							var tmp = new Huida(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.bu_chong, value.wen_ti);
							tmp.chuShiHua();
							han_shu.dm_arr[value.dai_ma].wen_ti[value.wen_ti].hui_da[value.xu_hao] = tmp;
							break;
						case 'fan_dui':
							var tmp = new Fandui(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.zhu_shi);
							tmp.chuShiHua();
							han_shu.dm_arr[value.dai_ma].zhu_shi[value.zhu_shi].fan_dui[value.xu_hao] = tmp;
							break;
						case 'bu_chong':
							var tmp = new Buchong(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.zhu_shi);
							tmp.chuShiHua();
							han_shu.dm_arr[value.dai_ma].zhu_shi[value.zhu_shi].bu_chong[value.xu_hao] = tmp;
							break;
						case 'fan_dui_hd':
							var tmp = new Fandui_hd(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.wen_ti, value.hui_da);
							tmp.chuShiHua();
							han_shu.dm_arr[value.dai_ma].wen_ti[value.wen_ti].hui_da[value.hui_da].fan_dui[value.xu_hao] = tmp;
							break;
						case 'bu_chong_hd':
							var tmp = new Buchong_hd(value.id, value.xu_hao, value.nei_rong, value.dai_ma, value.zuo_zhe, value.shi_jian, value.chl, value.zan_cheng, value.fan_dui, value.wen_ti, value.hui_da);
							tmp.chuShiHua();
							han_shu.dm_arr[value.dai_ma].wen_ti[value.wen_ti].hui_da[value.hui_da].bu_chong[value.xu_hao] = tmp;
							break;
					}  
				});
			});
			han_shu.xianShi_daima();
			han_shu.jiaZai_anniu_shijian();	//加载按钮事件
			han_shu.gaoLiang();
   		},
        error:function(xml){
         	alert("shibei!。。。。" + xml.responseText);
        }
   });
});