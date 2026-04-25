{{view('header')}}
<main>
<button id="crbutton">Create product</button>
    <div id="create_pool">

    </div>
    <script>
        document.getElementById('crbutton').addEventListener('click',function(){
            let xhr = new XMLHttpRequest();

            xhr.open('GET','/admin/products/create');
            xhr.setRequestHeader('Authorization','Bearer '+getCookie('auth_token'));
            xhr.send();
            xhr.onreadystatechange = function(){
                if(xhr.readyState == '4' && xhr.status == 200){
                    document.getElementById('create_pool').innerHTML = xhr.responseText;
                }
            }
        });

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for(let i = 0; i <ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
    </script>
</main>
{{view('footer')}}
