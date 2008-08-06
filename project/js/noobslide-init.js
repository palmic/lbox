/* vyzaduje SPECIALNE modifikovany noobslide */
window.addEvent('domready', function(){
var itemsContainer = $$('#carousel-top-model-box').getParent();
var itemsCollection = $$('#carousel-top-model-box li');
var carouselBox = $('carousel-top-model-box');
var buttonPrevious = $('carousel-previous');
var buttonNext = $('carousel-next');
var itemsDisplay = 4;
var autoPlay = true;
var autoPlayInterval = 5000/*ms*/;
if (!carouselBox)return;
var itemSizeX = $('carousel-top-model').getElement('.data .width-item').innerHTML; /* sirka obrazku*/
var itemSizeY = $('carousel-top-model').getElement('.data .height-item').innerHTML; /* vyska obrazku */
var items=new Array;for(var i=0;i<itemsCollection.length;i++){itemsCollection[i].setStyle('width',itemSizeX+'px');itemsCollection[i].getElement('img').setStyle('width',itemSizeX+'px');itemsCollection[i].setStyle('height',itemSizeY+'px');itemsCollection[i].getElement('img').setStyle('height',itemSizeY+'px');}for(var i=0;i<itemsCollection.length-itemsDisplay+1;i++){items[i]=i;}itemsContainer.setStyle('width',(itemSizeX*itemsDisplay)+'px');itemsContainer.setStyle('height',itemSizeY+'px');buttonPrevious.setStyle('height',itemSizeY+'px');buttonNext.setStyle('height',itemSizeY+'px');var ns1=new noobSlide({box:carouselBox,items:items,size:itemSizeX,sizeContainer:itemsCollection.length*itemSizeX,autoPlay:autoPlay,interval:autoPlayInterval,fxOptions:{duration:1000,transition:Fx.Transitions.Quad.easeOut,wait:false},addButtons:{previous:buttonPrevious,next:buttonNext}});});