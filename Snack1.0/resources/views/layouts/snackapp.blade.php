<html>
    <head>
        <!-- vueを追加-->
        <script src="https://unpkg.com/vue@2.5.17"></script>
        <!--jqueryを追加-->
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <!--csrf token-->
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
            th{background-color:#ffbf1f; color:fff; padding:5px 10px;}
            td{border:solid 1px #aaa; color:#999; padding:5px 10px;}
            
        </style>
         <link rel="stylesheet" href="{{asset('/css/snackapp.css')}}"><!--ここでcssを読み込む-->
         
    </head>
    <body>
        <h1>@yield('title')</h1>
        
        <hr size="1">
            <div class="content">
                @yield('content')
            </div>
        <div class="footer">
            @yield('footer')
        </div>

        <script src="{{asset('/js/snack_limit.js')}}"></script>
        <script src="{{asset('/js/member_limit.js')}}"></script>
    </body>
</html>