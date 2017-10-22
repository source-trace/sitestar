<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<LINK href="script/banner/a3/css/css.css" type=text/css rel=stylesheet>

<style type="text/css">
.ddindex_content_lz {
	 WIDTH: <?php echo $img_width;?>px; HEIGHT: <?php echo $img_height+36;?>px; background-color:#FFFFFF;margin:0 auto; padding:0; text-align:center;
}

#lantern {
	BORDER-RIGHT: #878787 0px solid; BORDER-TOP: #878787 0px solid; FONT-SIZE: 10.5pt; OVERFLOW: hidden; BORDER-LEFT: #878787 0px solid; WIDTH: <?php echo $img_width;?>px; CURSOR: pointer; LINE-HEIGHT: 23px; BORDER-BOTTOM: #878787 0px solid; HEIGHT: <?php echo $img_height+36;?>px
}

#lanternMain {
	WIDTH: <?php echo $img_width;?>px; HEIGHT: <?php echo $img_height;?>px; BACKGROUND-COLOR: #ffffff;
}

#lanternImg {
	OVERFLOW: hidden; left:0px; WIDTH: <?php echo $img_width;?>px; position:relative; HEIGHT: <?php echo $img_height;?>px; float:left;
}


</style>
<div id="img_heightnum" style="display:none; width:0px; height:0px;"><?php echo $img_height;?></div>
<div id="img_widthnum" style="display:none; width:0px; height:0px;"><?php echo $img_width;?></div>
<div id="img_alt_site_name" style="display:none; width:0px; height:0px;"><?php echo $curr_siteinfo->site_name;?></div>
<SCRIPT type=text/javascript>
var img_heightnum;
img_heightnum=0;

img_heightnum=document.getElementById('img_heightnum').innerHTML*1;


var img_widthnum;
img_widthnum=0;

img_widthnum=document.getElementById('img_widthnum').innerHTML*1;

var img_alt_site_name;
img_alt_site_name = document.getElementById('img_alt_site_name').innerHTML;
function getidname(id)
{
    return document.getElementById(id);
};

//ff支持s
function isIE()
{
  return !!(window.attachEvent && !window.opera);
}


if(!isIE()){ //firefox innerText define
   HTMLElement.prototype.__defineGetter__( "innerText", 
    function(){
     var anyString = "";
     var childS = this.childNodes;
     for(var i=0; i<childS.length; i++) {
      if(childS[i].nodeType==1)
       anyString += childS[i].tagName=="BR" ? '\n' : childS[i].innerText;
      else if(childS[i].nodeType==3)
       anyString += childS[i].nodeValue;
     }
     return anyString;
    } 
   ); 
   HTMLElement.prototype.__defineSetter__(     "innerText", 
    function(sText){ 
     this.textContent=sText; 
    } 
   ); 
}

//ff支持s
function getidname(id)
{
    return document.getElementById(id);
};

function isIE()
{
  return !!(window.attachEvent && !window.opera);
}

//首页幻灯
var Lantern={

    onChange:[],
    oInterval:[],
    otimeOut:[],
    opacityNum:101,
    cycNum:0,
    showNum:0,
    width:386,//整体宽度
    navyCtr:[],//2维:  0.原长 1.目标长 2.speed 
    navyTime:10,//navy动画时间
    picMoveSpeed:22,//图片移动速度
    timeOut_time:<?php echo $play_speed?$play_speed:5000;?>,//停滞时间
    info ://0.图片url 1.名称 2.链接地址 
    [],
    
    init: function(){
        Lantern.onChange=false;
        for(var i=0;i<Lantern.info.length;i++)
        {
        	//dump(Lantern.info);
            var picDiv
            var picTemp
            picDiv=document.createElement('div');
            picTemp=document.createElement('img');
	        picDiv.id ="LanternImg"+i;
            picDiv.name=i;
	        picTemp.src = Lantern.info[i][0];
	        picTemp.alt = img_alt_site_name;
	        picTemp.style.width = img_widthnum+"px";
			 picTemp.style.height = img_heightnum+"px";
	        picDiv.style.position ="absolute";
	        picDiv.style.left = img_widthnum+"px";
	        
	        picDiv.onclick=function(){
	        	if(Lantern.info[this.name][3]=="_blank"){
	        		window.open(Lantern.info[this.name][2]);
	        	}else if(Lantern.info[this.name][3]=="_self"){
	        		window.location.href=(Lantern.info[this.name][2]);
	        	}else{
	        		window.open(Lantern.info[this.name][2]);
	        	}
	        	
	        };
	        picDiv.appendChild(picTemp);
	        document.getElementById("lanternImg").appendChild(picDiv);
	        var divTemp
	        divTemp=document.createElement('div');
	        divTemp.id ="LanternN"+i;
	        divTemp.style.width="275px";
            divTemp.name=i;
	        divTemp.innerHTML="<div class='liclass' id='lanternnum"+i+"'>"+(i+1)+"</div><span id=\"__lanternNc"+i+"\" style=\"display:none\">&nbsp;<b>"+(i+1)+"</b>."+Lantern.info[i][1]+"</span>";
	        if(i==0)
            {
               divTemp.className ="div_off1";
            }
            else if(i==Lantern.info.length-1)
            {
                divTemp.className ="div_off3";
            }
            else
            {
                divTemp.className ="div_off2";
            }
	        //divTemp.className="div_off";
	        if(i==0)
	            divTemp.onclick=function(){window.open(Lantern.info[this.name][2]);};
	        else
	            divTemp.onclick=function(){if(!Lantern.onChange){Lantern.onChange=true;Lantern.setNavy(this.name);}};
	        document.getElementById("lanternNavy").appendChild(divTemp);
        }
        
        Lantern.initNany();
    },
    
    initNany:function(){
        navyCtr=new Array();
        for(var k=0;k<Lantern.info.length;k++)
            Lantern.navyCtr[k]=[];
        document.getElementById("__lanternNc0").style.display ="";
        document.getElementById("lanternnum0").style.display="none";
        document.getElementById("LanternN0").className ="div_on1";
        var onLength,offLength
        onLength=275//;document.getElementById("LanternN0").offsetWidth;
        offLength=27.75;//(Lantern.width-onLength)/(Lantern.info.length-1)
        var numtemp=0;
        for(var j=0;j<Lantern.info.length;j++)
        {
              if(j!=0)//未选
              {
                     Lantern.navyCtr[j][1]=offLength;
                     document.getElementById("__lanternNc"+j).style.display ="none";
                    if(j==Lantern.info.length-1)
                    {
                        document.getElementById("LanternN"+j).className ="div_off3";
                    }
                    else
                    {
                        document.getElementById("LanternN"+j).className ="div_off2";
                    }
                     document.getElementById("LanternN"+j).style.width=offLength+"px";
                     if(j==Lantern.info.length-1) 
                     {
                        document.getElementById("LanternN"+j).style.width=(Lantern.width-onLength-numtemp-7)+"px";  
                     }
                     else
                     {
                        numtemp+=offLength;
                     }
              }
              else//已选
              {
                 Lantern.navyCtr[j][1]=onLength;
              }
        }


        document.getElementById("LanternImg0").style.display ="";
        document.getElementById("LanternImg0").style.left ="0px";
        Lantern.otimeOut=setTimeout("Lantern.cycLantern()",Lantern.timeOut_time);
    },
    
    setNavy:function(i){
        if(i==Lantern.info.length-1)
             document.getElementById("lanternNavy").style.backgroundColor ="#F5F4F2";
        else
             document.getElementById("lanternNavy").style.backgroundColor ="#CCCABE";
             
        document.getElementById("__lanternNc"+i).style.display ="";
        document.getElementById("lanternnum"+i).style.display="none";
        if(i==0)
        {
            document.getElementById("LanternN"+i).className ="div_on1";
        }
        else if(i==Lantern.info.length-1)
        {
            document.getElementById("LanternN"+i).className ="div_on3";
        }
        else
        {
            document.getElementById("LanternN"+i).className ="div_on2";
        }
        document.getElementById("LanternN"+i).style.width=null;
        var onLength,offLength
        onLength=275;//document.getElementById("LanternN"+i).offsetWidth
        offLength=27.75;//(Lantern.width-onLength)/(Lantern.info.length-1)
        var numtemp=0;
        for(var j=0;j<Lantern.info.length;j++)
        {
              Lantern.navyCtr[j][0]=Lantern.navyCtr[j][1];
              if(i!=j)//未选
              {
                     Lantern.navyCtr[j][1]=offLength;
                     document.getElementById("__lanternNc"+j).style.display ="none";
                     document.getElementById("lanternnum"+j).style.display="";
                       if(j==Lantern.info.length-1)
                        {
                            document.getElementById("LanternN"+j).className ="div_off3";
                        }
                        else
                        {
                            document.getElementById("LanternN"+j).className ="div_off2";
                        }
                     if(j==Lantern.info.length-1) 
                     {
                        document.getElementById("LanternN"+j).style.width=(Lantern.width-onLength-numtemp-7)+"px";
                     }
                     else
                     {
                        numtemp+=offLength
                     }
                     document.getElementById("LanternN"+j).style.width=Lantern.navyCtr[j][0]+"px";
              Lantern.navyCtr[j][2]=(Lantern.navyCtr[j][1]-Lantern.navyCtr[j][0])/Lantern.navyTime ;
              }
              else//已选
              {
                 Lantern.navyCtr[j][1]=onLength;
                 document.getElementById("LanternN"+j).style.width=(Lantern.navyCtr[j][0])+"px";
              Lantern.navyCtr[j][2]=(Lantern.navyCtr[j][1]-Lantern.navyCtr[j][0])/Lantern.navyTime ;
             
              }
        }
        document.getElementById("LanternImg"+i).style.display ="";
        if(Lantern.onChange)
        {
                document.getElementById("LanternN"+i).onclick=function(){window.open(Lantern.info[this.name][2]);};
                document.getElementById("LanternN"+Lantern.showNum).onclick=function(){if(!Lantern.onChange){Lantern.onChange=true;Lantern.setNavy(this.name);}};
                document.getElementById("LanternImg"+i).style.zIndex=0;
                document.getElementById("LanternImg"+Lantern.showNum).style.zIndex=-1;
                Lantern.oInterval=setInterval('Lantern.changeLantern('+i+')',10);
        }
    },
    
    imgMoveOver:false,
    navyMoveOver:false,
     changeLantern:function(i){
            if(Lantern.otimeOut!=null)
                clearTimeout(Lantern.otimeOut)
             //move
             if(!Lantern.navyMoveOver)
                Lantern.moveNavy(i);
             if(!Lantern.imgMoveOver)
             {
                Lantern.moveImg(i);
             }
             else
             {
                Lantern.flashImg(i);
             }
    },
    
     moveNavy:function(select){
            var breaktime=0;
            for(var i=0;i<Lantern.info.length;i++)
            {
                  if((Lantern.navyCtr[i][2]>0&&document.getElementById("LanternN"+i).offsetWidth<Lantern.navyCtr[i][1])||(Lantern.navyCtr[i][2]<0&&document.getElementById("LanternN"+i).offsetWidth>Lantern.navyCtr[i][1]))
                  {
                       if(i==select)
                       {
                            document.getElementById("LanternN"+i).style.width=(document.getElementById("LanternN"+i).offsetWidth+Lantern.navyCtr[i][2]-7)+"px";  
                       }
                       else
                       {
                            document.getElementById("LanternN"+i).style.width=(document.getElementById("LanternN"+i).offsetWidth+Lantern.navyCtr[i][2])+"px";  
                       }
                          
                  }
                  else
                  {
                      if(i==select)
                      {
                           for(var j=0;j<Lantern.info.length;j++)
                           {
                                document.getElementById("LanternN"+j).style.width=Lantern.navyCtr[j][1]+"px"; 
                           }

                           Lantern.navyMoveOver=true;
                           break;
                  }
              }
            }
    },
    
     moveImg:function(i){
            if(document.getElementById("LanternImg"+i).offsetLeft>0)
            {
               document.getElementById("LanternImg"+i).style.left=(document.getElementById("LanternImg"+i).offsetLeft-Lantern.picMoveSpeed)+"px";
            }
            else
            {
                document.getElementById("LanternImg"+i).style.left="0px";
                document.getElementById("LanternImg"+Lantern.showNum).style.left=Lantern.width+"px";
                Lantern.imgMoveOver=true;
            }
    },
    
      flashImg:function(i){
             document.getElementById("LanternImg"+i).style.opacity="100"; 
                    Lantern.showNum=i;
                    Lantern.imgMoveOver=false;
                    Lantern.navyMoveOver=false;
                    Lantern.opacityNum=101;
                    Lantern.cycNum=i;
                    clearInterval(Lantern.oInterval);
                    Lantern.otimeOut=setTimeout("Lantern.otimeOut=Lantern.cycLantern()",Lantern.timeOut_time);
                    Lantern.onChange=false;
    },
      
    cycLantern:function(){
        if(!Lantern.onChange)
        {
            Lantern.onChange=true;
            if(Lantern.cycNum==Lantern.info.length-1)
                Lantern.cycNum=0;
            else
                Lantern.cycNum++;
           Lantern.setNavy(Lantern.cycNum)
        }
    },
    moveprevious:function(){
        if(!Lantern.onChange){
            
            if(Lantern.cycNum>0)
                Lantern.cycNum-=1;
            else
                return;
            
            Lantern.onChange=true;
            Lantern.setNavy(Lantern.cycNum)        
        }
    },
    movenext:function(){
        if(!Lantern.onChange){
            
            if(Lantern.cycNum>=Lantern.info.length-1)
                return ;
            else
                Lantern.cycNum+=1;        
             
                Lantern.onChange=true;                
                Lantern.setNavy(Lantern.cycNum);
        }
    }
    
    
}



</SCRIPT>
<DIV class=ddindex_content_lz id=__E_lunzhuan>
<DIV id=lantern>
<DIV id=lanternMain>
<DIV id=lanternImg></DIV></DIV>
<DIV 
style="BORDER-TOP: #ffffff 1px solid; FLOAT: left; BORDER-BOTTOM: #ffffff 1px solid"><IMG alt="<?php echo $curr_siteinfo->site_name;?>" onclick=Lantern.moveprevious(); src="script/banner/a3/images/index_banner_lz_02_left.gif"></DIV>
<DIV id=lanternNavy></DIV>
<DIV style="BORDER-TOP: #ffffff 1px solid; FLOAT: left; BORDER-BOTTOM: #ffffff 1px solid"><IMG alt="<?php echo $curr_siteinfo->site_name;?>" onclick=Lantern.movenext(); src="script/banner/a3/images/index_banner_lz_02_right.gif"></DIV>
<SCRIPT type=text/javascript>
     document.lanterninfo=function(){
   Lanterninfo=new Array();
   Lanterninfo=[    
      <?php
		$kkk=1;
		foreach($img_order as $k=>$v){
			$urlhttp="";
			$classname=',';
			$fname=',';
			$pushLink='';
			if($kkk==1){$classname='';}
			if($linkaddr[$k]&&$islink[$k]=='yes'){$urlhttp=$linkaddr[$k];}
			if($urlhttp=='http://'){$urlhttp='';}
			if($urlhttp){
				if (isset($img_open_type[$k+1][0])&&$img_open_type[$k+1][0]=='1'){ 
					$tag_target = "_self"; 
				}else{ 
					$tag_target = "_blank";
				} 
				
		?>
 <?php echo $classname;?>['<?php echo $img_src[$k]; ?>','<?php echo $sp_title[$k];?>','<?php echo $urlhttp;?>','<?php echo $tag_target; ?>']
               <?php
			}else{
			?>
 <?php echo $classname;?>['<?php echo $img_src[$k]; ?>','<?php echo $sp_title[$k];?>','#']
          <?php
			}
		
			$pushLink.=$fname.'"'.$urlhttp.'"';
			
		$kkk++;
		}
		?>   

       ];
       return Lanterninfo;
   } 
   Lantern.info=new Array();
   Lantern.info=document.lanterninfo();
   Lantern.init();
</SCRIPT>
</DIV></DIV>
