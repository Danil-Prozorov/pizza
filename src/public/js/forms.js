
document.getElementById('authorise_button').addEventListener('click',function(){
    let xhr = new XMLHttpRequest();
    let form = Array.from(document.getElementsByName('authform'))[0];
    let form_data = new FormData(form);
    let form_path = form.getAttribute('action');
    xhr.open('POST',''+form_path);
    xhr.send(form_data);
    xhr.onreadystatechange = function(){
        if(xhr.readyState == '4' && xhr.status == 200){
            let response = JSON.parse(xhr.response);
            let date = new Date();

            document.cookie = 'auth_token='+response.token+';expires='+date.getTime()+response.expires_in+';path=/';
        }
    }
});
