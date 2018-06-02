var i = 0;
var body = document.querySelector('body');
setInterval("body.setAttribute('style', 'background-position-x:" + i + "px');i++;",1);