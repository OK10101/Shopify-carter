<!doctype html>
<html lang="en">
<head>
    <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
    <script type="text/javascript">
        ShopifyApp.init({
            apiKey: '{{ Config::get('carter.shopify.client_id') }}',
            shopOrigin: 'https://{{ Auth::user()->domain }}',
            debug: true
        });
    </script>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="background-color: #ebeef0">

@yield('content')

@yield('shopify_script')
<script type="text/javascript">
    ShopifyApp.ready(function(){
        ShopifyApp.Bar.loadingOff()
    });
</script>
</body>
</html>