//songxin livechannel page
$.extend({
    weibolive:function(setting){
        var defaultOpt = {
            timeStep : 12000,
            sinceId:0,
            lastId:0,
            show_feed:0,
            follow_gid:0,
			hasUid:0,
            gid:0,
            weiboType:0,
            weiboListDiv:"#feed_list",
            loadMoreLiveDiv:'#loadMoreLiveDiv',
            type:0,
            typeList:{
                WEIBO:0,
                GROUP:1,
				NEWS:3,
                ALL:2
            },
            hoverClass:'hover',
            loadNewDiv:'#countLiveNew'
        },opt={},self=this,popup=null,isLoading=false;
        
        $.extend(opt, defaultOpt, setting);
        var operateFactory = new OperateFactory(opt.type);
        
        $.extend(this,{
                obj:null,
                countLiveNew:function(){	

                    if(!opt.show_feed && opt.lastId>0){
                        setInterval(function(){
                            operateFactory.create('countLiveNew',function(txt){
                                if(txt.indexOf('<TSAJAX>')==0) {
								//
                                    if(txt.indexOf('<HASNEW>')!=-1) {
                                        $('#countLiveNew').html(txt);
                                        events.loadLiveNew();
                                    }else{ //songxin add
										$('#countLiveNew').html("");
									}  //songxin end
                                }else{
                                    //location.reload();
                                }
                            });
                        },15000);//songxin edit 修改迭代时间
                    }
                },
				setLastIdByWeiboListDiv:function(){
                   setLastIdByWeiboListDiv();
                },
                plugin:{}
        });
        
        function OperateFactory(nowType){
            var post=function(type,otherParam){
                var param = {};
                for(var one in type.param){
                    if(opt[type.param[one]] != undefined){
                        param[one] = opt[type.param[one]];
                    }else{
                        param[one] = type.param[one];
                    }
                }
                if(otherParam){
                    param = $.extend(param, otherParam);
                }
                $.post(type.url,param,type.callback);
            };
            
            var weibo={
                countLiveNew:{
                   url:U('weibo/index/countlivenew'),
                   param:{hasUid:'hasUid',lastId:'lastId',showfeed:'show_feed',type:'weiboType',follow_gid:'follow_gid'}
                },
                loadLiveNew:{
                    url:U('weibo/index/loadlivenew'),
                    param:{hasUid:'hasUid',since:'lastId',showfeed:'show_feed',type:'weiboType',follow_gid:'follow_gid'}
                },
                loadLiveMore:{
                    url:U('weibo/index/loadlivemore'),
                    param:{hasUid:'hasUid',since:'sinceId',showfeed:'show_feed',type:'weiboType',follow_gid:'follow_gid'}
                }
            }
            
            var type;
            switch(nowType){
                case opt.typeList.WEIBO:
                    type = weibo;
                    break;
                case opt.typeList.GROUP:
                    type = group;
                    break;
				case opt.typeList.NEWS:
                    type = news;
                    break;
                case opt.typeList.ALL:
                    type = all;
                    break;
                default:
                    type = weibo;
            }
            
            
            this.create = function(commond,callback,params){
                var temp = type[commond];
                if(temp != undefined){ 
                    temp.callback = callback;
                    post(temp,params);
                }
            }
        }

		setLastIdByWeiboListDiv=function(){
            opt.lastId = $(opt.weiboListDiv).find('li:first').attr('id').split("_").pop();
        }
		
        var events = {
                loadLiveNew:function(){
                   // if(_MID_ <= 0){
                    //    return ;
                   // }
                    $(opt.loadNewDiv).find('a').click(function() {
                        var limit = $(this).attr('limit');
                        operateFactory.create("loadLiveNew",function(txt){
                            if(txt.indexOf('<TSAJAX>')==0){
                                if(txt.indexOf('<HASNEW>')!=-1) {
                                    $(opt.loadNewDiv).html('');
                                    $(opt.weiboListDiv).prepend(txt);
                                    setLastIdByWeiboListDiv();
                                }else{ //songxin add
									$(opt.loadNewDiv).html('');
									setLastIdByWeiboListDiv();
								} //songxin end
                            }else{
                                location.reload();
                            }
                        },{limit:limit});
                    });
                },
                lodeLiveMore:function(){
                     $(opt.loadMoreLiveDiv).click(function() {
                        $(this).html('加载中...');
                        var self = this;
                        loadMoreCount = typeof(loadMoreCount) == 'undefined' ? 0 : loadMoreCount;
                        operateFactory.create("loadLiveMore",function(txt){
                            clearInterval(isLoading);
                            isLoading = false;
                            loadMoreCount++ ;
                            if(parseInt(opt.sinceId) !== 0) {
                                $(opt.weiboListDiv).append(txt);
                            }
                            try{
                                // var tempSinceId = $(opt.weiboListDiv).find('li:last').attr('id').split("_").pop();
                                var tempSinceId = $(opt.weiboListDiv).find("li[id^='list_li_']").last().attr('id').split('_').pop();
                            }catch(e){
                                var tempSinceId = false;
                            }
                            opt.sinceId = typeof(sinceId) == 'undefined' ? tempSinceId : (tempSinceId || sinceId) ;
                            //判断没有更多数据时.不显示更多按钮
                            if(txt.indexOf('<HASNEW>')==-1){
                                $(self).parent().html('<span class="morefoot">没有更多数据了</span>');
                            }else{
                                //if(loadMoreCount<5){
                                    $(self).html('<span class="ico_morefoot"></span>更多');
                                //}else{
                                    //显示分页
                                //  $(self).html('这里是分页');
                                //}
                            }
                            //loadmore后修改弹窗黑背景的高度
                            var obj = document.getElementById('boxy-modal-blackout');
                            if(obj !== null) {
                                $('#boxy-modal-blackout').css('height', document.body.clientHeight + 100);
                            }
                        });
                    });
                },
				
                scrollResize:function(){
                    if(opt.initForm){
                        var loadCount = 0;
                        $(window).bind('scroll resize',function(event){
                            if(loadCount <3 && !isLoading){
                                var bodyTop = document.documentElement.scrollTop + document.body.scrollTop;
                                //滚动到底部时出发函数
                                //滚动的当前位置+窗口的高度 >= 整个body的高度
                                if(bodyTop+$(window).height() >= $(document.body).height()){
                                    isLoading = true;
                                    $(opt.loadMoreDiv).click();
                                    loadCount ++;
                                }
                            }
                        });
                    }
                },
        }
            
        for(var one in events){
                events[one]();
        }
                
        var start = function(){
            self.countLiveNew();//激活定式更新微博数事件
            if(opt.initForm){
               self.initForm(opt.publishForm.form.split('#').pop(),{ enter:function(formObj,buttonObj,contentObj,numObj,txt){after_publish(txt);}});
            }
          
            return self;
        }
                
        return start();
    }
});

var CallBack = function(){
    return{
        Vote:{}
    }
}

CallBack.Vote = {
    addSuccess:function(data){ }
}

//songxin end