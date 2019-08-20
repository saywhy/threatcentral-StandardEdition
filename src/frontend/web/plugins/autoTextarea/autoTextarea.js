$.fn.autoTextarea = function(seting){
  $(this).each(function(){
    var inputEvent = "";
    var auto = $(this);
    var s;
    function update() {
      if(auto[0].autoTextareaSeting){
        s = auto[0].autoTextareaSeting;
      }else{
        return;
      }
      if(inputEvent == "input")
      {
        auto.height(s.miniHeight);
      }
      var h = s.miniHeight;
      if(this.scrollHeight > s.oldScrollHeight && this.scrollHeight > s.miniHeight)
      {
        h = this.scrollHeight;
      }
      if(/^[0-9]*$/.test(s.maxHeight) && this.scrollHeight > s.maxHeight )
      {
        h = s.maxHeight;
        auto.css("overflow","auto");
      }
      auto.height(h);
    }
    function init() {
      s = {
        miniHeight:auto.height(),
        maxHeight:"auto",
        oldScrollHeight:auto[0].scrollHeight
      }
      for(var key in seting)
      {
        s[key] = seting[key];
      }
      auto[0].autoTextareaSeting = s;
      if(typeof auto[0].oninput != "undefined")
      {
        inputEvent = "input";
      }else if(typeof auto[0].onpropertychange != "undefined")
      {
        inputEvent = "propertychange";
      }
      auto.css("resize","none");
      auto.css("overflow","hidden");
      auto.off(inputEvent);
      auto.on(inputEvent,update);
    }
    if(typeof seting == 'string'){
      if(seting == 'update'){
        update();
      }
    }
    if(typeof seting == 'object' || typeof seting == 'undefined'){
      init();
    }
  });
}