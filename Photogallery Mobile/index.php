<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Photogallery</title>
        
        <link href="css.css" rel="stylesheet" type="text/css">
	</head>
    
	<body>
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
							echo "<a href='http://www.mattjonsson.com/photos/", $row['name'],"'><img src='http://www.mattjonsson.com/photos/thumbs/", $row['name'], "' alt='Photo' class='thumb'></a>";
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
	</body>
</html>