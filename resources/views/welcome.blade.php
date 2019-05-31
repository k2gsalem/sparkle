<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                
                background-color: #33334d;
                color: #fff;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }
            .subtitle{
                font-size: 20px;
            }

            .links > a {
                color: #e1e4ea;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/myaccount') }}">My Account</a>
                    @else
                        <a href="/user/login">Login</a>

                        @if (Route::has('register'))
                            <a href="/user/register">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

        <div class="content">
            <div class="title">
                    Photoz
                </div>
                <div class="subtitle m-b-md">
                    A Photo Gallery App by Trijeet Ganguly
                </div>
                

                <div class="links">
                    <a href="/dash">Dashboard</a>
                    <a href="/about">About</a>
                    <a href="/users">Users</a>
                </div>
            </div>
        </div>
    </body>
</html>
