(function($) {
    var ColorHex = new Array('00','33','66','99','CC','FF');
    var SpColorHex = new Array('FF0000','00FF00','0000FF','FFFF00','00FFFF','FF00FF');
    $.fn.colorpicker = function(options){
        var opts = $.extend({}, this.colorpicker.defaults, options);
        initColor(opts);
        return this.each(function(){
            var obj = $(this);
            // Color面板定位
            var style = {
                "top": obj.offset().top-150,
                "left": obj.offset().left + 45,
                "z-index": opts.zIndex
            };
            if ($.isPlainObject(opts.css) && !$.isEmptyObject(opts.css))
            	style = $.extend({}, style, opts.css);
            $("#colorpanel").css(style);
      		// 绑定Color格子事件
            $("#CT tr td").unbind("click").mouseover(function(){
            	var color = $(this).css("background-color");
                $("#DisColor").css("background",color);
                $("#HexColor").val($(this).attr("rel"));
            }).click(function(){
                opts.success(obj, $(this).attr("rel"));
                destroy();
            });
            // "关闭"Color面板相关
            $("#_cclose").css({
            	"color": "#666",
            	"cursor": "pointer",
                "font-size": "12px"
            }).bind('click',function(){
                destroy();
                return false;
            });
            // 设置"Transparent"
            $("#TomColor").click(function(){
            	opts.success(obj, 'transparent');
                destroy();
            });
            // 自定义颜色
            var icolor;
            $("#HexColor").keydown(function(event){
            	icolor = ($(this).val() || "").replace('#','');
            	// Enter
            	if (/^[a-z0-9]+$/ig.test(icolor) && (event.keyCode == 13)) {
            		opts.success(obj, '#'+icolor);
                	destroy();
            	}
            }).keyup(function(){
            	icolor = ($(this).val() || "").replace('#','');
            	if(/^[a-z0-9]+$/ig.test(icolor)) $("#DisColor").css("background", '#'+icolor);
            });
        });    
    };
    // 生成Color面板
    function initColor(opts){
        $(opts.appendTo).append('<div id="colorpanel" style="position:absolute;"></div>');
        var colorTable = '';
        var colorValue = '';
        for(i=0;i<2;i++){
            for(j=0;j<6;j++){
                colorTable=colorTable+'<tr height="12">'
                colorTable=colorTable+'<td width="11" rel="#000000" style="background-color:#000000;height:11px;">'
                colorValue = i==0 ? ColorHex[j]+ColorHex[j]+ColorHex[j] : SpColorHex[j];
                colorTable=colorTable+'<td width="11" rel="#'+colorValue+'" style="background-color:#'+colorValue+';height:11px;">'
                colorTable=colorTable+'<td width="11" rel="#000000" style="height:11px;background-color:#000000">'
                for (k=0;k<3;k++){
                    for (l=0;l<6;l++){
                        colorValue = ColorHex[k+i*3]+ColorHex[l]+ColorHex[j];
                        colorTable=colorTable+'<td width="11" rel="#'+colorValue+'" style="height:11px;background-color:#'+colorValue+'">'
                    }
                }
            }
        }
        colorTable = '<table width="253" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #000;border-bottom:0">'
        +'<tr height=30><td colspan="21" bgcolor="#cccccc">'
        +'<table cellpadding="0" cellspacing="1" border="0" style="border-collapse:collapse"><tr>'
        +'<td width="3">&nbsp;</td><td><input type="text" id="DisColor" size="6" disabled style="border:1px solid #666;background-color:#000" /></td>'
        +'<td width="3"></td><td><input type="text" id="HexColor" size="7" maxlength="7" style="border:1px solid #666" value="#000000" /></td><td width="3"></td>'
        +'<td><img id="TomColor" border="0" src="../images/tmcolor.gif" width="16" height="16" title="Transparent" style="cursor:pointer" /></td>'
        +'<td width="3"></td><td width="123" align="right"><span id="_cclose" title="Close">[×]</span></td></tr></table></td></table>'
        +'<table id="CT" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#000000" style="cursor:pointer;">'
        +colorTable+'</table>';
        $("#colorpanel").html(colorTable);
    }
	// 销毁Color面板
	function destroy() {
		$("#colorpanel").remove();
	}
    
    $.fn.colorpicker.defaults = {
        zIndex: 1000,
		appendTo:'body',
		css : {}, // 颜色面板style
        success:function(){} //回调函数
    };
})(jQuery);