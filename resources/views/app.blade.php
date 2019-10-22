<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>qBittomatic</title>
    <link rel="stylesheet" href="dist/app.css" />
</head>
<body>
    <h1>qBittomatic</h1>
    <div id="app">
        <h3>Hello App!</h3>
        <ul>
            <li><router-link to="/foo">Go to Foo</router-link></li>
            <li><router-link to="/bar">Go to Bar</router-link></li>
        </ul>

        <router-view></router-view>
    </div>
    <script src="dist/app.js"></script>
</body>
</html>