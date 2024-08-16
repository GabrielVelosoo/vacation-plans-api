<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Vacation Plan</title>
        <meta charset="utf-8">
        <style>
            html, body{
                height: 100%;
                margin: 0;
                font-family: "Times New Roman", Times, serif;
            }

            .content-page{
                width: 100%;
                height: 100%;
                text-align: left;
            }

            .title h1{
                padding: 80px 0px 0px 0px;
                text-align: center;
                color: #42403f;
                font-size: 45px;
            }

            .title h2{
                padding: 0px 0px 50px 0px;
                text-align: center;
                color: #5d5957;
                font-size: 30px;
            }

            .info-page{
                text-align: left;
                margin-top: 60px;
            }

            p{
                color: #42403f;
                font-size: 18px;
                font-weight: 500;
                overflow-wrap: break-word;
                display: block;
            }

            .plan{
                width: 75%;
                margin-left: auto;
                margin-right: auto;
            }

            h3{
               color: #42403f;
               font-size: 27px;
            }
        </style>
    </head>
    <body>

        @yield('body')

    </body>
</html>