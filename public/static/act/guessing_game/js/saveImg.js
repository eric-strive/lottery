/**
 * create by liu.zy
 */
function saveImg(){
//    event.preventDefault();
    html2canvas(document.getElementById("picc"), {
    allowTaint: true,
    taintTest: false,
    onrendered: function(canvas) {
        canvas.id = "mycanvas";
        //生成base64图片数据
        var dataUrl = canvas.toDataURL();
        var newImg = document.createElement("img");
        newImg.src =  dataUrl;
        $('#picc .erweiamma').hide();
        $('#picc .imhgh').empty().append(newImg);
	    }
	});
}