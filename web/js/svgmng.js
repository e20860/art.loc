var svg = document.querySelector("svg");
var btn = document.getElementById("inbut");
var aOn = 25;
var oKnp = {
    "x": 0,
    "y": 0,
    "h": 0
};
var oOp = {
    "x": 0,
    "y": 0,
    "h": 0
};
var oTgt = {
    "num": 0,
    "x": 0,
    "y": 0,
    "h": 0
};


function knp(x,y){
	var pnt = document.createElementNS("http://www.w3.org/2000/svg",'use');
	pnt.setAttributeNS("http://www.w3.org/1999/xlink", "href", "#knp");
	pnt.setAttribute("x", new String(x));
	pnt.setAttribute("y", new String(y));
	svg.appendChild(pnt);
}

function op(x,y){
	var pnt = document.createElementNS("http://www.w3.org/2000/svg",'use');
	pnt.setAttributeNS("http://www.w3.org/1999/xlink", "href", "#op");
	pnt.setAttribute("x", new String(x));
	pnt.setAttribute("y", new String(y));
	pnt.style.transformOrigin = getTransformOriginStr(x,y);
	pnt.style.transform = getTransformStr();
	svg.appendChild(pnt);
}

function tgt(x,y){
	var pnt = document.createElementNS("http://www.w3.org/2000/svg",'use');
	pnt.setAttributeNS("http://www.w3.org/1999/xlink", "href", "#target");
	pnt.setAttribute("x", new String(x));
	pnt.setAttribute("y", new String(y));
	pnt.style.transformOrigin = getTransformOriginStr(x,y);
	pnt.style.transform = getTransformStr();
	svg.appendChild(pnt);
}

function getTransformOriginStr(x,y){
	return new String(Math.round(x/500*100)+'% ' + Math.round(y/500*100)+'% 0');
}

function getTransformStr(){
	var angle = aOn <= 30 ? (aOn*6):(aOn*6-360);
	return new String('rotate(' + angle + 'deg)');
}

function mapGrid(){
	for(var i = 0; i<600;i+=100){
		drawLine(0,i,500,i);
		drawText(5,i+90,45-i/100);
	}
	for(i = 0; i<600;i+=100){
		drawLine(i,0,i,500);
		drawText(i+5,15,17+i/100);
	}
}

function drawLine(x1,y1,x2,y2){
	var ln = document.createElementNS("http://www.w3.org/2000/svg",'line');
	ln.setAttribute("x1", new String(x1));
	ln.setAttribute("y1", new String(y1));
	ln.setAttribute("x2", new String(x2));
	ln.setAttribute("y2", new String(y2));
	svg.appendChild(ln);
}

function drawText(x,y,txt){
	var tt = document.createElementNS("http://www.w3.org/2000/svg",'text');
	tt.setAttribute("x", new String(x));
	tt.setAttribute("y", new String(y));
	tt.appendChild(document.createTextNode(txt));
	svg.appendChild(tt);
}

// Функции перевычисления координат
function getQuadrant(alpha){
	return Math.floor(alpha/5)+1;
}
function getX0(yop,quadrant){
	var delta = [0,-2,-1,0,0,0,-1,-2,-3-4,-4,-4,-3];
	return Math.floor(yop/1000)+delta[quadrant];
}

function getY0(xop,quadrant){
	var delta = [0,0,0,-1,-2,-3,-4,-4,-4,-3,-2,-1,0];
	return Math.floor(xop/1000)+delta[quadrant];
}

function getXCoord(y,x0) {
	return Math.round((y-x0*1000)/10);
}

function getYCoord(x,y0) {
	return 500 - Math.round((x-y0*1000)/10);
}

function getCoords(){
	var fjs = document.getElementById('fjs').files[0];
	var reader = new FileReader();

	  reader.readAsText(fjs);

	  reader.onload = function() {
		//console.log(reader.result);
		var crds = JSON.parse(reader.result);
		console.log(crds.knp);
	  };

	  reader.onerror = function() {
		console.log(reader.error);
	  };	
}
function fillForm(ctrl) {
    var file = ctrl.files[0];
    var reader = new FileReader();
    reader.readAsText(file);
    reader.onload = function () {
        var crds = JSON.parse(reader.result);
        document.getElementById('aon').value = crds.aon;
        document.getElementById('xknp').value = crds.knp.x;
        document.getElementById('yknp').value = crds.knp.y;
        document.getElementById('hknp').value = crds.knp.h;

        document.getElementById('xop').value = crds.op.x;
        document.getElementById('yop').value = crds.op.y;
        document.getElementById('hop').value = crds.op.h;

        document.getElementById('ntgt').value = crds.tgt.num;
        document.getElementById('xtgt').value = crds.tgt.x;
        document.getElementById('ytgt').value = crds.tgt.y;
        document.getElementById('htgt').value = crds.tgt.h;

    };

    reader.onerror = function () {
        console.log(reader.error);
    };
}

function setBattleOrder() {
    aOn = document.getElementById("aon").value;

    oKnp.x = document.getElementById("xknp").value;
    oKnp.y = document.getElementById("yknp").value;
    oKnp.h = document.getElementById("hknp").value;

    oOp.x = document.getElementById("xop").value;
    oOp.y = document.getElementById("yop").value;
    oOp.h = document.getElementById("hop").value;

    oTgt.num = document.getElementById("ntgt").value;
    oTgt.x = document.getElementById("xtgt").value;
    oTgt.y = document.getElementById("ytgt").value;
    oTgt.h = document.getElementById("htgt").value;


    var qq = getQuadrant(aOn);
    var x0 = getX0(oOp.y, qq);
    var y0 = getY0(oOp.x, qq);

    mapGrid();
    op(getXCoord(oOp.y, x0), getYCoord(oOp.x, y0));
    knp(getXCoord(oKnp.y, x0), getYCoord(oKnp.x, y0));
    tgt(getXCoord(oTgt.y, x0), getYCoord(oTgt.x, y0));

}

function saveBattleOrder() {
    var battleOrder = {
        "aon": document.getElementById("aon").value,
        "knp": {
            "x": document.getElementById("xknp").value,
            "y": document.getElementById("yknp").value,
            "h": document.getElementById("hknp").value
        },
        "op": {
            "x": document.getElementById("xop").value,
            "y": document.getElementById("yop").value,
            "h": document.getElementById("hop").value
        }
    };
    var data = JSON.stringify(battleOrder);
    console.log(data);
    writeFile("/data/bo.json",data);
}

function writeFile(name, value) {
    
    var val = (value === undefined) ? "" : value;
    var download = document.createElement("a");
    download.href = "data:text/json;content-disposition=attachment;filename=file,"+val;
    download.download = name;
    download.style.display = "none";
    download.id = "download";
    document.body.appendChild(download);
    document.getElementById("download").click();
    document.body.removeChild(download);
    
}		
		
		
