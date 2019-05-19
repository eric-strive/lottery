!(function(doc, win) {
    var docEle = doc.documentElement,
        evt = "onorientationchange" in window ? "orientationchange" : "resize",
        fn = function() {
            var width = docEle.clientWidth;
            width && (docEle.style.fontSize = 20 * (width / 320) + "px");
        };
     
    win.addEventListener(evt, fn, false);
    doc.addEventListener("DOMContentLoaded", fn, false);
 
}(document, window));


/*Play sound component*/
/*
 * profile:     JSON, {src:'chimes.wav',altSrc:'',loop:false}
 * 
 * setSrc:     Function, set the source of sound
 * play:        Function, play sound
 */
if (!FUI){
    var FUI = {};
}
FUI.soundComponent=function(profile){
    this.profile={
        src:'',　　　　　　　　　  //音频文件地址
        altSrc:'',　　　　　　　　 //备选音频文件地址 （不同浏览器支持的音频格式不同，可见附表）
        loop:false　　　　　　　  //是否循环播放，这个参数现在没有用上
    };
    if(profile) {
        $.extend(this.profile,profile);
    }
    this.soundObj=null;
    this.isIE = !-[1,];
 /*这个方法是前辈大牛发明的，利用ie跟非ie中JScript处理数组最后一个逗号“,”的差异,
 不过对于IE 9，这个办法就无效了，但此处正合我用，因为IE 9支持audio*/
    this.init();
};
FUI.soundComponent.prototype={
    init:function(){
        this._setSrc();
    },        
    _setSrc:function(){
        if(this.soundObj){                
            if(this.isIE){
                this.soundObj[0].src=this.profile.src;    
            }else{
                this.soundObj[0].innerHTML='<source src="'+this.profile.src+'" />'+
'<source src="'+this.profile.altSrc+'" />';    
            }    
        }else{
            if(this.isIE){
                this.soundObj=$
('<bgsound volume="-10000" loop="1" src="'+this.profile.src+'">').appendTo('body');
 //-10000是音量的最小值。先把音量关到最小，免得一加载就叮的一声，吓到人。
            }else{
                this.soundObj=$('<audio preload="auto" autobuffer>'+
'<source src="'+this.profile.src+'" />'+
'<source src="'+this.profile.altSrc+'" />'+
'</audio>').appendTo('body');
            }                
        }            
    },
    setSrc:function(src,altSrc){
        this.profile.src=src;
        if(typeof altSrc!='undefined'){
            this.profile.altSrc=altSrc;
        }        
        this._setSrc();
    },
    play:function(){
        if(this.soundObj){
            if(this.profile.loop){
                this.soundObj[0].setAttribute("loop","loop");
            }
            if(this.isIE){
                this.soundObj[0].volume = 1;　　//把音量打开。
                this.soundObj[0].src = this.profile.src;
            }else{
                this.soundObj[0].play();
            }
        }

    },
    stop:function () {
        if(this.soundObj){
            if(this.profile.loop){
                this.soundObj[0].pause()
            }
        }
    }
};

