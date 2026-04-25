<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Food-доставка еды на дом</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap&subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
<div class="wrapper head">
    <header class="wrapper_head">
        <a href="{{route('home')}}"><img src="img\logo.svg" alt="logo" class="logo"></a>
        <input type="search" class="input address_input" placeholder="Поиск">
        <div class="wrapper_button">
            @if(!auth('api')->user())
            <a href="{{route('auth.login')}}">
                <button class="button_primary">
                    <img src="img\user.svg" class="button_icon">
                    <span class="button_text">Войти</span>
                </button>
            </a>
            @endif
            <a href="{{route("cart.index")}}">
                <button>
                    <img src="img\shopping-cart.svg" class="button_icon">
                    <span class="button_text">Корзина</span>
                </button>
            </a>
        </div>
    </header>
</div>
