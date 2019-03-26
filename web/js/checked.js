var btcocher = document.getElementById("btCocher"); 
btcocher.addEventListener("click",function(){
    var chckbox = document.getElementsByClassName("check");
    for (let item of chckbox) {
        item.checked = "true";
    };
});