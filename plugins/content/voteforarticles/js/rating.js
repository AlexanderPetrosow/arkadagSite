(function(a){
    a.fn.rating_actia = function(p){
        var p = p||{};
        var b = p&&p.starLength?p.starLength:"5";
        var c = p&&p.callbackFunctionName?p.callbackFunctionName:"";
        var e = p&&p.initialValue?p.initialValue:"0";
        var d = p&&p.imageDirectory?p.imageDirectory:"images";
        var r = p&&p.inputAttr?p.inputAttr:"";
        var z = p&&p.inputAttri?p.inputAttri:"";
        var x = p&&p.inputAttrx?p.inputAttrx:"";
        var y = p&&p.inputAttrx?p.inputAttrx:"";
        var f = e;
        var g = a(this);
        b = parseInt(b);
        init();
        g.next("ul").children("li").hover(function(){
            jq(this).parent().children("li").css('background-position','0px 0px');
            var a = jq(this).parent().children("li").index(jq(this));
            jq(this).parent().children("li").slice(0,a+1).css('background-position','0px -28px')
        },function(){});
        g.next("ul").children("li").click(function(){
            var a = jq(this).parent().children("li").index(jq(this));
            var attrVal  = (r != '')?g.attr(r):'';
            var attrVali = (z != '')?g.attr(z):'';
            var attrValx = (x != '')?g.attr(x):'';
            var attrValy = (y != '')?g.attr(y):'';
            f = a+1;
            g.val(f);
            if(c != ""){
                eval(c+"("+g.val()+", "+attrVal+")");
            }
        });
        g.next("ul").hover(function(){},function(){
            if(f == ""){
                jq(this).children("li").slice(0,f).css('background-position','0px 0px')
            }else{
                jq(this).children("li").css('background-position','0px 0px');
                jq(this).children("li").slice(0,f).css('background-position','0px -28px')
            }
        });
        function init(){
            jq('<div style="clear:both;"></div>').insertAfter(g);
            g.css("float","left");
            var a = jq("<ul>");
            a.addClass("rating_actia");
            for(var i=1;i<=b;i++){
                a.append('<li style="background-image:url('+d+'actia_star.gif)"><span>'+i+'</span></li>')
            }
            a.insertAfter(g);
            if(e != ""){
                f = e;
                g.val(e);
                g.next("ul").children("li").slice(0,f).css('background-position','0px -28px')
            }
        }
    }
})(jQuery);