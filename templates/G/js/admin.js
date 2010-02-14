function swapImage(ImageID, mode) {

var myImage = document.getElementById(ImageID);
var mySrc = myImage.src;
var newSrc = mySrc.replace(/_(\w+).jpg/, "_" + mode + ".jpg");
//alert(newSrc);
myImage.src = newSrc;

}

function focusElement(Element) {

document.getElementById(Element).focus();
return true;

}