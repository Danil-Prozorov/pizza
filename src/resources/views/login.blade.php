{{view('header')}}
    <div class="wrapper">
        <div class="form_register">
            <form name="authform" action="{{route('auth.loginRequest')}}" method="POST">
                @csrf
                <div class="form_fields">
                    <input type="text" name="email" placeholder="Email">
                    <input type="password" name="password" placeholder="Пароль">
                </div>
                <div class="wrapper_button">
                    <button id="authorise_button" type="button" class="button_primary">Войти</button>
                    <a href="{{route('auth.register')}}">Зарегестрироваться</a>
                </div>
            </form>
        </div>
    </div>
{{view('footer')}}
