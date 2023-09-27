// JavaScript Document
var CopyClipboardButton = {};
CopyClipboardButton.getCopyText = function (b) {
    b = document.getElementById(b);
    try {
        return b.value || b.innerText || b.textContent
    } catch(c) {
        return ""
    }
};
CopyClipboardButton.appendButton = function (b, c, f) {
    asd = document.getElementById(b);
    c = CopyClipboardButton.create(c, f);
    asd.appendChild(c)
};
CopyClipboardButton.listen = function (b, c, f) {
    if (b.addEventListener) b.addEventListener(c, f, false);
    else if (b.attachEvent) return b.attachEvent("on" + c, f)
};
CopyClipboardButton.targ = function (b) {
    var c;
    if (!b) b = window.event;
    if (b.target) c = b.target;
    else if (b.srcElement) c = b.srcElement;
    if (c.nodeType == 3) c = c.parentNode;
    return c
};
CopyClipboardButton.create = function (b, c) {
    var f = {
        height: "21",
        width: "115",
        fontSize: "14",
        fontColor: "#000000",
        fontFace: "Helvetica",
        pathToSwf: "CopyClipboardButton.swf?v=3.0",
        imageUrl: "",
        copyText: "",
        wmode: "transparent"
    };
    if (typeof c == "undefined") c = {};
    for (var l in c) f[l] = c[l];
    c = document.createElement("embed");
    l = document.createElement("object");
    l.height = f.height;
    c.height = l.height;
    l.width = f.width;
    c.width = l.width;
    c.setAttribute("type", "application/x-shockwave-flash");
    var r = document.createElement("param");
    r.name = "movie";
    r.value = f.pathToSwf;
    l.appendChild(r);
    c.setAttribute("src", r.value);
    r = document.createElement("param");
    r.name = "FlashVars";
    r.value = "copyTextContainerId=" + b + "&fontSize=" + f.fontSize + "&fontFace=" + f.fontFace + "&fontColor=" + f.fontColor + "&imageUrl=" + f.imageUrl + "&copyText=" + f.copyText;
    l.appendChild(r);
    c.setAttribute("flashVars", r.value);
    r = document.createElement("param");
    r.name = "quality";
    r.value = "high";
    l.appendChild(r);
    r = document.createElement("param");
    r.name = "menu";
    r.value = "false";
    l.appendChild(r);
    r = document.createElement("param");
    r.name = "wmode";
    r.value = f.wmode;
    l.appendChild(r);
    c.setAttribute("wmode", f.wmode);
    try {
        l.appendChild(c)
    } catch(A) {
        c = document.createElement("textarea");
        f = f.copyText ? f.copyText : document.getElementById(b).innerHTML;
        c.appendChild(document.createTextNode(f));
        c.setAttribute("style", "display:none;");
        c.setAttribute("class", "hidden");
        c.setAttribute("className", "hidden");
        b = b + "__cont";
        c.setAttribute("id", b);
        document.body.appendChild(c);
        f = document.createElement("a");
        f.appendChild(document.createTextNode('_'));
        f.href = "#";
       f.setAttribute("id", "short_url__cont_anchor");
        f.setAttribute("rel", b);
		//alert(b);
        CopyClipboardButton.listen(f, "click", function (u) {
            u = CopyClipboardButton.targ(u);
            if ((u = document.getElementById(u.rel)) && u.innerHTML != "") {
                cont = u;
                u.createTextRange().execCommand("Copy")
            }
        });
        return f
    }
    return l
};

function addInfoCopyButton() {
    var b = {
        height: "21",
		width: "115",
		imageUrl: "../images/bluebutton-big-clipboard.gif",
        pathToSwf: "../FusionCharts/CopyClipboardButton.swf",
        
    };
  CopyClipboardButton.appendButton("info_copy_button", "short_url", b);
}

function copyaddress()
{    
    name=$("#name1").val();
    add=$("#address").val();
    bname=$("#businessname").val();
    place=$("#place").val();
    dis=$("#district option:selected").text();
    state=$("#state option:selected").text();
    pin=$("#pincode").val();
    phone=$("#phone1").val();
    cell=$("#cell1").val();
    email=$("#emailid1").val();
    fulladd=name+"\n"+bname+"\n"+add+"\n"+place+"\n"+dis+"\n"+state+"\n"+pin+"\n"+phone+"\n"+cell+"\n"+email;
    //alert(fulladd);
   
   var copyadd = document.createElement("textarea");
  // Add it to the documet
  document.body.appendChild(copyadd);
  // Set its ID
  copyadd.setAttribute("id", "copy_id");
  // Output the address into it  
  document.getElementById("copy_id").value=fulladd;  
  // Select it
  copyadd.select();
  // Copy its contents
  document.execCommand("copy");
  // Remove it as its not needed anymore
  document.body.removeChild(copyadd);
   
  
}

//added on 16-07-2019
function copyaddressdealer()
{
    contactperson=$("#contactperson").val();
    add=$("#address").val();
    bname=$("#businessname").val();
    place=$("#place").val();
    dis=$("#district option:selected").text();
    state=$("#state option:selected").text();
    pin=$("#pincode").val();
    phone=$("#phone").val();
    cell=$("#cell").val();
    email=$("#emailid").val();
    fulladd=contactperson+"\n"+bname+"\n"+add+"\n"+place+"\n"+dis+"\n"+state+"\n"+pin+"\n"+phone+"\n"+cell+"\n"+email;
    //alert(fulladd);

    var copyadd = document.createElement("textarea");
    // Add it to the documet
    document.body.appendChild(copyadd);
    // Set its ID
    copyadd.setAttribute("id", "copy_id");
    // Output the address into it
    document.getElementById("copy_id").value=fulladd;
    // Select it
    copyadd.select();
    // Copy its contents
    document.execCommand("copy");
    // Remove it as its not needed anymore
    document.body.removeChild(copyadd);
}
