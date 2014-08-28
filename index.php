<?php
include 'classes/api.php';
$api = new ShotpicAPI();
$token = $_GET['token'];
$metadata = $api->getFileMetadata($token);
?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NKuh's cowshot</title>
<link href="http://fonts.googleapis.com/css?family=Abel|Arvo" rel="stylesheet" type="text/css" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />
<script src="//ajax.googleapis.com/ajax/libs/mootools/1.4.5/mootools-yui-compressed.js"></script>
<script type="text/javascript" src="js/index.js"></script>
<script type="text/javascript">
	var token = "<?php echo  isset($_GET['token']) ? $_GET['token'] : ''; ?>";
	<?php

	if(isset($metadata) && isset($metadata['drawings'])) echo 'var drawings = ' . json_encode($metadata['drawings']); ?>;
</script>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>
<div class="container">
    <header>
    	<div class="logoContainer">
            <h1><a href="?"><span>cowshot</a></h1>
            <p>Shoot your screen right now!</p>
        </div>
        <br class="clearfloat" />
    </header>
    <section>
		<?php
		if(isset($token)) : ?>
		   	<div class="image">
		   		<a class="fullsizeLink" href="imgproxy.php?token=<?php echo $token; ?>" target="_blank"><img src="images/arrow_expand.png"  title="Open full-sized image in new window"></a>
		   		<?php if(isset($metadata)) {
			   		echo "<ul class='metadata'>";
			   		foreach ($metadata as $key => $value) {
			   			echo "<li><strong>$key:</strong> $value</li>";
			   		}
			   		echo "</ul>";
				}
		   		?>
		   		<div id="pictureArea">
		   			<img class="shot" src="imgproxy.php?token=<?php echo $token; ?>" alt="<?php echo $token; ?>">
		   		</div>
    		</div>
		<?php endif; ?>

        <div class="navcontainer">
            <ul class="navlist">
                <li id="active"><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Clients</a></li>
                <li><a href="mailto:leachim2k+cowshot@leachim2k.de">Contact</a></li>
            </ul>
        </div>
        <div class="welcomeContainer">
        	<h2>Was ist denn das?</h2>
            <p>
            	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. <a href="#">It was popularised in the 1960s</a> with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </p>
        </div>
        <?php
        /*
        <div class="content2">
        	<h2>Recently with desktop</h2>
            <p>
            	<span class="picContainer picImg"><img src="images/pic.jpg" alt=""></span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. <a href="#">It has survived not only five centuries</a>, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </p>
            <button>More</button>
        </div>
        <!-- begin .sidebar1 -->
        <div class="sidebar1">
        	<h3>When an unknown</h3>
            <p>
            	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown
            </p>
        	<h3>Categories</h3>
            <ul class="sidebarlist">
                <li><a href="#">Lorem Ipsum is simply dummy </a></li>
                <li><a href="#">Been the industry's standard</a></li>
                <li><a href="#">When an unknown printer took</a></li>
                <li><a href="#">Galley of type and scrambled</a></li>
                <li><a href="#">Only five centuries but also</a></li>
            </ul>
        </div>
        <!-- end .sidebar1 -->
        <!-- begin .mainContent -->
        <div class="mainContent">
       		<h3>It was popularised in the 1960s</h3>
            <p>
            	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.<br><br>
                <strong>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</strong><br><br>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of
            </p>
        </div>
        <!-- end .mainContent -->
        <br class="clearfloat" />
        */ ?>
    </section>
</div>
<footer>
    <p>
        Copyright &copy; nkuh. All rights reserved
    </p>
</footer>



</body>
</html>
