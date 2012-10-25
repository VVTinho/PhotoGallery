<?php
	include("mdetect.php");
	$uagent_obj = new uagent_info();

	function AutoRedirectToProperHomePage()
	{
		global $uagent_obj;
		
		if ($uagent_obj->isTierIphone == $uagent_obj->true)
		{ 
		  	header ("Location: http://m.mattjonsson.com/");
		}
		else if ($uagent_obj->DetectMobileQuick() == $uagent_obj->true)
		{
		  	header ("Location: http://m.mattjonsson.com/");
		}

	}
	AutoRedirectToProperHomePage();
	
	session_start();
	
	if (isset($_SESSION['login']))
	{
		if ($_SESSION['login'] == true)
		{
			if(isset($_FILES['photo']))
			{	
				$info = getimagesize($_FILES['photo']['tmp_name']);
				if ($info[2] == IMAGETYPE_JPEG) 
				{
					if (filesize($_FILES['photo']['tmp_name']) <= 10485760 ) 
					{
						createImages();
					} 
					else
					{
						echo "Max image size is 10MB";
					}
				}
				else
				{
					echo "Only jpg images allowed";
				}
			}
			else if (isset($_POST['logout_btn']))
			{
				session_unset();
				session_destroy();
				header("Location: http://mattjonsson.com/");
			}
			else if (isset($_GET['delete']))
			{
				$con = mysqli_connect("mysql04.citynetwork.se","107188-pa43635","dinmamma","107188-photogallery");
				if (!$con)
				{
					echo("Kunde inte ansluta till databasen.");
				}
				
				if (!mysqli_set_charset($con, "utf8")) {
					echo("Kunde inte sätta rätt teckentyp.");
				}
				
				$result = mysqli_query($con, "DELETE FROM photos WHERE name = '".$_GET['delete']."'");
				if (!$result) 
				{
					echo("Kunde inte radera från databasen.");
				}
				else
				{
					unlink('photos/'.$_GET['delete']);
					unlink('photos/thumbs/'.$_GET['delete']);
				}
				
				if (!mysqli_close($con))
				{
					echo("Kunde inte stänga anslutningen.");
				}
			}
		}
	}
	else if (isset($_POST['name']) && isset($_POST['password']))
	{
		$con = mysqli_connect("mysql04.citynetwork.se","107188-pa43635","dinmamma","107188-photogallery");
		if (!$con)
		{
			echo("Kunde inte ansluta till databasen.");
		}
		
		if (!mysqli_set_charset($con, "utf8")) {
			echo("Kunde inte sätta rätt teckentyp.");
		}
		
		$username = mysqli_real_escape_string($con, strip_tags($_POST['name']));
		$password = md5("v9qtv3th" . mysqli_real_escape_string($con, strip_tags($_POST['password'])) . "erfg89t3");		

		$result = mysqli_query($con, "SELECT name FROM users WHERE name = '$username' AND password = '$password'");
		if (!$result) 
		{
			echo 'Wrong username or password';
		}
		else if(mysqli_num_rows($result) == 1)
		{
			$_SESSION['user'] = $username;
			$_SESSION['login'] = true;
		}
		else
		{
			echo 'Wrong username or password';	
		}
		
		if (!mysqli_close($con))
		{
			echo("Kunde inte stänga anslutningen.");
		}	
	}
	else
	{
		session_unset();
		session_destroy();
	}
	
	function createImages()
	{            
		$original = imagecreatefromjpeg($_FILES['photo']['tmp_name']);
	
		$width = imagesx($original);
		$height = imagesy($original);
		
		if ($width >= $height)
		{
			$imageWidth = 1000;
			$thumbWidth = 150;
			$imageHeight = ceil(($height / $width) * 1000);
			$thumbHeight = ceil(($height / $width) * 150);
		}
		else
		{
			$imageWidth = ceil(($width / $height) * 700);
			$thumbWidth = ceil(($width / $height) * 150);
			$imageHeight = 700;
			$thumbHeight = 150;
		}
		
		$image = imagecreatetruecolor($imageWidth,$imageHeight);
		$thumb = imagecreatetruecolor($thumbWidth,$thumbHeight);
		
		$name = (string)time() . (string)rand(0, 9) . ".jpg";
		$imagename = "photos/". $name;
		$thumbname = "photos/thumbs/". $name;
		
		imagecopyresampled ( $image , $original , 0 , 0 , 0 , 0 , $imageWidth , $imageHeight , $width , $height );
		imagecopyresampled ( $thumb , $original , 0 , 0 , 0 , 0 , $thumbWidth , $thumbHeight , $width , $height );
		imagejpeg($image, $imagename, 100);
		imagejpeg($thumb, $thumbname, 100);
		imagedestroy($image);
		imagedestroy($thumb);
		imagedestroy($original);
	
		$con = mysqli_connect("mysql04.citynetwork.se","107188-pa43635","dinmamma","107188-photogallery");
		if (!$con)
		{
			echo("Kunde inte ansluta till databasen.");
		}
		
		if (!mysqli_set_charset($con, "utf8")) {
			echo("Kunde inte sätta rätt teckentyp.");
		}
		
		$result = mysqli_query($con, "INSERT INTO photos (name, width, height) VALUES ( '$name', $imageWidth, $imageHeight )");
		if (!$result) 
		{
			echo("Kunde inte hämta från databasen.");
		}
		
		if (!mysqli_close($con))
		{
			echo("Kunde inte stänga anslutningen.");
		}
	}

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Photogallery</title>
        
        <link href="css.css" rel="stylesheet" type="text/css">
        
		<script type="text/javascript" src="jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="js.js"></script>
	</head>
    
	<body>
    	<?php
    		if ($_SESSION['login'] == true)
			{
				echo '<div id="logout_div"></div>';
				echo '<div id="upload_div"></div>';
			}
			else
			{
				echo '<div id="login_div"></div>';
			}
		?>
        
        <div class="backdrop"></div>
        <div class="box"></div>
        <div id="wrapper">
        
        	<?php
                    $con = mysqli_connect("mysql04.citynetwork.se","107188-pa43635","dinmamma","107188-photogallery");
                    if (!$con)
                    {
                        echo("Kunde inte ansluta till databasen.");
                    }
					
					if (!mysqli_set_charset($con, "utf8")) {
						echo("Kunde inte sätta rätt teckentyp.");
					}
					
					if ($_SESSION['login'] == true)
					{
						$result = mysqli_query($con, "SELECT name, width, height FROM photos");
					}
					else
					{
						$result = mysqli_query($con, "SELECT name, width, height FROM photos ORDER BY RAND() LIMIT 0, 21");
					}
                    if (!$result) 
                    {
                        echo("Kunde inte hämta från databasen.");
                    }
                    
                    if(mysqli_num_rows($result) != false)
                    {
                        while($row = mysqli_fetch_array($result))
                        {
                            echo "<div class='image'>";
                          	echo "<span class='align'></span>";
							
							if ($_SESSION['login'] == true)
							{
								echo "<a href='index.php?delete=".$row['name']."' class='delete'>X</a>";
							}
							
							echo "<img src='photos/thumbs/", $row['name'], "' alt='Photo' class='thumb' onclick='imageclick(\"".$row['name']."\", ".$row['width'].",".$row['height'].")'>";
                            echo "</div>";
                        }
                    }
                    else
                    {
                        echo("Hittade inget i databasen.");
                    }
                    
                    if (!mysqli_close($con))
                    {
                        echo("Kunde inte stänga anslutningen.");
                    }
            ?>
        
        </div>
        
        <?php
        	if ($_SESSION['login'] == true)
			{
				echo
				'<div id="upload">
					<form id="file_upload" method="post" action="http://mattjonsson.com/" enctype="multipart/form-data">
						<input type="file" name="photo">
						<input type="submit" value="Spara">
					</form>
				</div>
				
				<div id="logout">
					<form id="logout_form" method="post" action="http://mattjonsson.com/">
						<input type="submit" name="logout_btn" id="logout_btn" value="Log out">
						<label for="logout_btn">'.$_SESSION['user'].'</label>
					</form>
				</div>';
			}
			else
			{
				echo 
				'<div id="login">
					<form id="login_form" method="post" action="http://mattjonsson.com/">
						<input type="text" name="name" id="name" class="login">
						<input type="password" name="password" id="password" class="login">
						<input type="submit" class="hide">
					</form>
				</div>';			
			}
		?>
	</body>
</html>