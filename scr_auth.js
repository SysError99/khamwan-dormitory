//auth status check
auth = document.createElement('iframe');
auth.id = 'session'
auth.style.display = 'none'
document.body.appendChild(auth)
//auth type
authType = document.getElementById('page').getAttribute("type")
switch(authType){
    case 'admin': 
        auth.src = 'scr_auth.php?admin'
        break;
    default: 
        auth.src = 'scr_auth.php?room'
        break;
}
//timer
setInterval(() => {
    if(auth.contentWindow.name==='invalid'){
        //kick back to login
        window.open('scr_auth.php','_self')
    }
},100)
