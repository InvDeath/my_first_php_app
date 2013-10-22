<html>
<head>
<title>{%LNG_TITLE%}</title>
<script type="text/javascript" src="../js/script.js"></script>
</head>
<style>
html, body {padding: 0; margin: 0;}
a {text-decoration: none;}
body {background-color: #ccc;}
.header {width: 960px; margin: 0 auto; border: 1px dashed; border-bottom: none;}
.main-wrapper {width: 960px; margin: 0 auto; border: 1px dashed; height:100%}
.sidebar {float:left; width: 200px; border-right: 1px dashed; height:100%;}
.footer {width: 960px; margin: 0 auto; border: 1px dashed; border-top: none;}
</style>
<body onload="changeDays();">
<a href="/">home</a><br />
		{%TPL_MAIN%}
</body>

</html>
