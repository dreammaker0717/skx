function Flipper(selector, settings) {
	
    this.settings = Object.assign({
        delay: 50,
      to:undefined
    }, settings || {});
    
    this.dom = {};
    this.build(selector);
    if(!isNaN(parseInt(this.settings.to))) {
        this.to(this.settings.to);
    }
  }
  Flipper.prototype = {
      next(cv, num ){
        
        if(cv==num) return; //reached
        else if(cv<num) cv++;
        else if(cv>num) cv--;
        
        if(window.requestAnimationFrame)
            window.requestAnimationFrame(this.next.bind(this,cv,num));
        else 
            this.dom.timer = setTimeout(this.next.bind(this,cv,num),1);
        this.dom.el.value = cv;
    },
      to(num) {
        var cv = parseInt(this.dom.el.value)||0;              
      this.dom.timer = setTimeout(this.next.bind(this,cv,num),this.settings.delay);
    },
      build( selector ) {
        var el = typeof selector == 'string' 
          ? document.querySelector(selector) 
        : selector;
      this.dom.el = el;
    }
  };