<html>
    <head>
    <!--mypage/home でjqueryを利用する-->
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <!--イイネ機能に向けたcsrf_tokenの設定--> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>@yield('title')</title>

    
        
        <style>
            body {font-size:16pt; color:#999; margin: 5px;}
            h1 {font-size:50pt; text-align:right; color:pink;
            margin:-20px 0px -30px 0px; letter-spacing:-4pt;}
            ul{font-size:12pt;}
            hr{margin:25px 100px; border-top:1px dashed #ddd;}
            .menutitle{font-size:14pt; font-weight:bold; margin:0px;}
            .content{margin:10px;}
            .footer{text-align:right; font-size:10pt; margin:10px;
            border-bottom:solid 1px #ccc; color:#ccc;}
            th{background-color:#ffbf1f; color:#fff; padding:5px 10px;}
            td{border:solid 1px #aaa; color:#999; padding:5px 10px;}
            article{
                width:70%;
                order:3;
                border:double 3px #999;
            }
            aside{
                width:27%;
                order:1;
                border:double 3px #999;
                height:50%;
            }
            .search{
                order:2;
                width:70%;
                margin:5px 5px 5px 3px;
                height:60%;
                
            }
            .main-wrapper{
                display:flex;
                justify-content:space-between;
                margin-bottom:50px;
            }
        </style>
         <link rel="stylesheet" href="{{asset('/css/snackapp.css')}}">
                
    </head>
    <body>
        <h1>@yield('title')</h1>
        
        <hr size="1">
        
        <div class="main-wrapper">
            <aside>
                <div class="subbar">
                    @yield('subbar')
                </div>
                <div class="suggests">
                    @yield('suggests')
                </div>     
            </aside>
            
            <article>
                <div class="content">
                    <div class="search">
                        @yield('search')
                    </div>
                    @yield('content')
                </div>
            </article>
        </div><!--main-wrapper-->
        <div class="footer">
            @yield('footer')
        </div>
    <!--ここでjsファイルを利用する-->
    <script src="{{asset('/js/like.js')}}"></script>
    </body>
</html>