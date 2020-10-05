<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>@yield("title", "Where is your title?")</title>

  <link rel="stylesheet" href="{{ asset("css/app.css") }}">
  <link rel="stylesheet" href="{{ asset("css/layout.css") }}">
  @stack("styles")
</head>
<body>

<div id="app" class="container">
  @yield("content")
</div>

<script src="{{ asset("js/app.js") }}"></script>
@yield("scripts")
</body>
</html>
