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
<div class="container-fluid" id="app">
<div class="row flex-nowrap">
  <div class="logo">
          <router-link :to="{name: 'Home'}"><img src="images/logo.png"></router-link>
  </div>
</div>
<div class="row flex-nowrap">
@if(Auth::check())
@include('sidebar')
@endif
<div class="col py-3">
      <router-view :user-data="{{auth()->user() ?? '0'}}"></router-view>
      <vue-progress-bar></vue-progress-bar>
</div>
  <div class="clearfix"></div>
  <div class="footer">
    <p>&copy;COPYRIGHT 2021 - ABCD SALES</p>
  </div>
</div>
</div>
<script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>