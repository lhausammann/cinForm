<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<script type="text/javascript" src="../js/lib/jquery-1.4.2.js"></script>
<script type="text/javascript" src="../js/lib/jquery.validate.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Insert title here</title>

<!-- get jQuery -->


<style type="text/css">

span.caption {
	font-weight: bold;
	color: red;
}

.error, .error input, .error textarea, .error select {
	border: 1px solid red;
	background-color: #FFEEEE;
	
}

/* add some css3 magic */
span.error {
	background: -moz-linear-gradient(100% 100% 180deg, #FF1111, #FFEEEE);
	background: -webkit-gradient(linear, left top, right top, from(#FF0000), to(#FFEEEE));
}

.

ul.error {
	padding: 10px;
	list-style-type: none;
	width: 780px;
}

.error a {
	text-decoration:none;
	color: red;
}

div.wrapper.error {
	border:none;
	border-bottom: 1px solid #FFFFFF;
}

ul.errors li {
	margin-left: 15px;
}

ul.errors li a {
	color: #FF0000;
	text-decoration: none;
}

.wrapper {
	position: relative;
	width: 100%;
	background-color:#EEFFEE;
	padding-top: 10px;
	padding-bottom: 10px;
	border-bottom: 1px solid #FFFFFF;
}

label {
	display:block;
	text-align:right;
	float:left;
	width: 300px;
	padding-right:15px;
}

input,textarea, select {
	width: 475px;
}
input {
	height: 25px;
}

 input.radio {
	vertical-align:center;
	text-align: left;
	width: 50px;
	height: 15px;
}

br {
	clear: both;
}

span.error {
	display: block;
	position: absolute;
	margin: 10px;
	padding: 5px;
	border: 1px solid red;
	background-color: #FFEEEE;
	width:175px;
	top: 10px;
	left:400px;
}

.wrapper:hover > span.error, input:focus > span.error, textarea:focus > span.error {
	display: none;
}



</style>
</head>
<body>
<?php echo $form->render(); ?>
<?php echo $form->getJQueryValidation();?>
</body>
</html>