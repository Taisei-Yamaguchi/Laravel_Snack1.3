Vueの確認
<!DOCTYPE html>
<html lang="ja">
<head>
<script src="https://unpkg.com/vue@2.5.17"></script>

<!--とりあえず、↓でjqueryはできる-->
<script src="https://code.jquery.com/jquery-3.4.1.js"></script> 
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laravel</title>
<!--  @vite(['resources/css/app.css', 'resources/js/app.js']) -->
<style>
  .submenu h3{
    margin:0 0 1em 0;
    font-size:16px;
    cursor:pointer;
    color:#5e78c1;
  }
  .submenu h3:hover{
    color:#b04188;
    text-decoration:underline;
  }
  .submenu ul{
    margin: 0 0 1em 0;
    list-style-type: none;
    font-size:14px;
  }
  .hidden{
    display:none;
  }
  </style>
</head>


<body>
<section>
  <div class="sidebar">
    <h2>サポートページ</h2>
    <div class="submenu">
      <h3>1. Jqueryの確認</h3>
      <ul class="hidden">
        <li><a href="">-jqueryは使える</a></li>
        <li><a href="">-1</a></li>
      </ul>
    </div>
</section>
<!-- jqueryの利用はできる -->
    <script>
      'use strict';

      $(document).ready(function(){
        $('.submenu h3').on('click',function(){
          $(this).next().toggleClass('hidden');
        });
      });
    </script>
        
</body>
</html>
