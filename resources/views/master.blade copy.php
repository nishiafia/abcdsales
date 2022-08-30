<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="user-id" content="{{ optional(Auth::user())->id }}">
  <meta name="user-type" content="{{ optional(Auth::user())->usertype }}">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>BDWHOLESALE Inventory System</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">
  <div class="logo">
    <router-link :to="{name: 'Home'}"><img src="images/logo.png"></router-link><br>
    <!--span class="text-logo">BDWHOLESALLING.COM</span-->
</div>
@if(Auth::check())
@include('sidebar')
@endif
  <div class="content-wrapper">
    <div class="content">
      <div class="container-fluid">
        <router-view :user-data="{{auth()->user() ?? '0'}}"></router-view>
        <vue-progress-bar></vue-progress-bar>

      </div>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="footer">
    <p>&copy;COPYRIGHT 2021 - ABCD SALES</p>
  </div>
</div>
<script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>