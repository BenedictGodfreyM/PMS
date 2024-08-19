<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>

    <title>Your Website Is Ready - InfinityFree</title>

    <link href="assets/css/bootstrap.css" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <style type="text/css">
        body{

        }
        /*     General overwrite     */
        a{
            color: #2C93FF;
        }
        a:hover, a:focus {
            color: #1084FF;
        }
        a:focus, a:active,
        button::-moz-focus-inner,
        input[type="reset"]::-moz-focus-inner,
        input[type="button"]::-moz-focus-inner,
        input[type="submit"]::-moz-focus-inner,
        select::-moz-focus-inner,
        input[type="file"] > input[type="button"]::-moz-focus-inner {
            outline : 0;
        }

        /*           Typography          */

        p{
            font-size: 16px;
            line-height: 1.6180em;
        }

        .main{
            background-image: url('assets/img/background.jpeg');
            background-position: center center;
            background-size: cover;
            height: auto;
            left: 0;
            min-height: 100%;
            min-width: 100%;
            position: absolute;
            top: 0;
            width: auto;
        }
        .cover{
            position: fixed;
            opacity: 1;
            background-color: rgba(0,0,0,.6);
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .cover.black{
            background-color: rgba(0,0,0,.6);
        }

        .logo-container .logo{
            overflow: hidden;
            border-radius: 50%;
            border: 1px solid #333333;
            width: 60px;
            float: left;
        }

        .main .logo{
            color: #FFFFFF;
            font-size: 56px;
            font-weight: 300;
            position: relative;
            text-align: center;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.33);
            margin-top: 150px;
            z-index: 3;
        }
        .main .logo.cursive{
            font-family: 'Grand Hotel',cursive;
            font-size: 82px;

        }
        .main .content{
            position: relative;
            z-index: 4;
        }
        .main .motto{
            min-height: 140px;
        }
        .main .motto, .main .subscribe .info-text{
            font-size: 28px;
            color: #FFFFFF;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.33);
            text-align: center;
            margin-top: 50px;

        }
        .main .subscribe .info-text{
            font-size: 18px;
            margin-bottom: 30px;
        }

        .footer{
            position: relative;
            bottom: 20px;
            right: 0;
            width: 100%;
            color: #AAAAAA;
            z-index: 4;
            text-align: right;
            margin-top: 50px;
        }
        .footer a{
            color: #FFFFFF;
        }

        @media (min-width:991px){
            .footer{
                position: fixed;
                bottom: 20px;
            }
        }

    </style>

    <!--     Fonts     -->
    <link href="assets/plugins/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>

<body>
<div class="main">
    <div class="cover black" data-color="black"></div>
    <div class="container">
        <h1 class="logo cursive">
            Welcome to Pharmacy Management System 
        </h1>

        <div class="content">
            <h4 class="motto">Your administrator account is all set up!,&nbsp;you will be redirected automatically to the login page in <span id="countdown">10</span> seconds.</h4>
            <div class="subscribe">
                <h5 class="info-text">
                    If you are not redirected automatically,&nbsp;<a href="?url=login">click here</a>&nbsp;to continue.
                </h5>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="container">
            Proudly powered by <br>
            <a href="https://adminlte.io/themes/v3">
                <img src="assets/img/AdminLTELogo.png" alt="AdminLTE" height="40px">
                AdminLTE
            </a>
        </div>
    </div>
</div>
<script src="assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js" type="text/javascript"></script>
<script src="assets/js/welcome.js" type="text/javascript"></script>
</body>
</html>
