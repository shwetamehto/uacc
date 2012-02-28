 <?php

	//ini_set('display_errors', 'On');
	//error_reporting(E_ALL);	
	session_start();
	/* User logs in with default building */
	$default_building_name = $_SESSION['mylocation'];
	
	if(!isset($_SESSION['member_id']))
	{
		session_destroy();
		/*If so, take them right to the page*/
		header("location:index.php");
	}
	
 ?>


<!DOCTYPE HTML>
<html lang="en">
 

    <meta charset="utf-8">
    <title>UACC: Campus Connect</title>
       
    <link rel="stylesheet" href="http://uacc.prographicstech.com/style.css" media="screen">
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false">
    </script>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://layout.jquery-dev.net/lib/js/jquery.layout-latest.js"></script>

    <script type="text/javascript">
    
  
 <?php
     /*Connect to the database*/
     $link = mysql_connect("localhost","prograph_dbUser","dbUser@uacc");
     mysql_select_db("prograph_uacc")or die (mysql_error());
     
     /* Extract default building id  from database table named building */
     $result= mysql_query("SELECT building_id FROM Building WHERE name LIKE '$default_building_name'") or die (mysql_error()); 
     
     if (!$result) 
     {
     	die('Invalid query: ' . mysql_error());
     }
     $build_id = mysql_result($result, 0);  
     mysql_close($link);
 ?> 
    
 
  
   myMap = null;

//Variables associated with with 'building_id' & 'user_id'
   login_building = "<? echo $build_id ?>";
   login_user = "<? echo $_SESSION['member_id'];?>" ; 
      
     
 <?php
     /*Connect to the database to get building information for combo box */
     $db = mysql_connect("localhost","prograph_dbUser","dbUser@uacc");
     mysql_select_db("prograph_uacc")or die (mysql_error());
     
     /* Extract a row  from database table named building */
     $result= mysql_query("SELECT * FROM Building ") or die (mysql_error());  
     if (!$result) 
     {
     	die('Invalid query: ' . mysql_error());
     }
     $result_array = array();
     while($row = mysql_fetch_assoc($result))
     {
        $result_array[] = $row;
     }
     mysql_close($db);
 ?> 
 
 
     // Decoding in javascript 
     buildings = jQuery.parseJSON('<? echo json_encode($result_array)?>');
   

 
      function MyMap(map)
      {
        this._map = map;
      }
	  

      function goToBuilding(entityId)
      {
        building_id = parseInt(entityId.value);
        if(building_id == -1)
        {
          return;
        }

        var index = -1;
        for(i = 0; i < buildings.length; ++i)
        {
          if(buildings[i].building_id == entityId.value)
          {
            index = i;
            break;
          }
        }

        if(index == -1)
        {
          return;
        }

        myMap.focus(buildings[index].lat, buildings[index].lon);
      }


      function createDropDownBuildings(inputArray)
      {
        var htmlTxt = '\<select onchange=\"goToBuilding\(this\)\" \>\n'
                      + '\<option value=\'-1\'\>Select Building\<\/option\>\n';
        for(i = 0; i < inputArray.length; ++i)
        {
          htmlTxt += '\<option value=\'' + inputArray[i].building_id + '\'\>'
                     + inputArray[i].name + '\<\/option\>\n';
        }
        htmlTxt += '\<\/select\>';

        return htmlTxt;
      }

 
      function createComboForBuildings(inputArray)
      {
        document.getElementById('combo').innerHTML = createDropDownBuildings(inputArray);
      }

 
      function drawBuildings(buildings)
      {
        for(i = 0; i < buildings.length; ++i)
        {
          myMap.drawMarker(buildings[i].building_id, buildings[i].lat, buildings[i].lon);
        }
      }


      function updateStatus(status)
      {
      		//alert(login_building);
      		//alert(t);
      		
		var o = status.currentTarget, t = $('#status'), s = $.trim($(t).val());
		//alert(o);
		//alert(t);
		//alert(s);
		if(s == "") return;
		$(o).attr("disabled",true);
		$(t).val("");
	$.post('post.php', 
		{ u: login_user, c: s, m: login_building, t : (new Date()).getTime() },
		function(res) {
			var data = res;
			try{ data = eval("("+res+")");
			}catch(e){data = null;}
			if(!data || data.success == "0")
			{
				alert("There's an error. Please try again.");				
				$(t).val(s);
			}else				
			$(o).attr("disabled",false);
			//gt();
			
	});

      }

 
      function initialize()
      {
        var myOptions = {
          center: new google.maps.LatLng(42.686221,-73.823962),
          zoom: 17,
          minZoom:16,
          mapTypeId: google.maps.MapTypeId.HYBRID,
		  streetViewControl : false,
          navigationControlOptions: {
          style: google.maps.NavigationControlStyle.SMALL
          },
          mapTypeControlOptions: {
          style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
         }
        };

        var map = new google.maps.Map(document.getElementById("map_canvas"),
                  myOptions);

        myMap = new MyMap(map);
      }

 
      MyMap.prototype.center = function()
      {
        this._map.panTo(new google.maps.LatLng(42.686126,-73.823833));
        this._map.setZoom(17);
      }

 
      MyMap.prototype.focus = function(lat, lon, zoomNum)
      {
        zoomNum = typeof(zoomNum) != 'undefined' ? zoomNum : 20;
        this._map.panTo(new google.maps.LatLng(lat, lon));
        this._map.setZoom(zoomNum);
      }

      MyMap.prototype.drawMarker = function(id, lat, lon)
      {
        var marker = new google.maps.Marker({
              position: new google.maps.LatLng(lat, lon),
              map: this._map,
              title:"Click here"
          });
		marker._parent = this._map;
        marker._id = id;
		marker._info = "";
		marker._infowin = new google.maps.InfoWindow({
			position:new google.maps.LatLng(lat,lon),
			map:this._map,
			content: marker._info,
			maxWidth: 300
        });
		marker._infowin.close();
        google.maps.event.addListener(marker, 'click', function(e)
        {
			var that = this, win = that._infowin;
			try{ win.close(); 
			}catch(ex){}
			win.setContent("Loading Data...");
			win.open(that._parent, that);
			
			$.get("get.php", {t : (new Date()).getTime(), m:that._id }, 
			// I didn't see any data in 'Building' table. So I use '0' as 'building_id' here.
   function(res){
	if(res == null || res.success == "0")
	{
		that._infowin.setContent("There's an error. Please try again.");
		that._infowin.open(that._parent, that);
	}
//	ct();
	var data = res.data, txt="<ul>";
	for(var i=0; i<data.length; i++)
	{
		txt += "<li>" + "<b>"+data[i].first_name + " "+ data[i].last_name +": " + "</b>" + data[i].content + " [" +data[i].time +"]</li>";
	}
	txt += "</ul>";
	that._infowin.setContent(txt);
	that._infowin.open(that._parent, that);
//	st();
   }, "json");

        });

      }

 </script>

 <script type="text/javascript">
    
    
    function prepare()
    {
       initialize();
       drawBuildings(buildings);
       createComboForBuildings(buildings);
    }
  </script>

  <script type="text/javascript">
    $(document).ready(function ()
    {
      $('body').layout({ applyDefaultStyles: true });
      prepare();
    });
  </script>

  <body>
      <div class="ui-layout-north" >
      <img src="UAlbany_logo.png" align="left" width ="40%" ></img>
      </br>
      <h2><center>WELCOME TO UACC CAMPUS CONNECT</center></h2>
      </br>
 <?php
	if(isset($_SESSION['front_message']))
	{
	   echo "<center><p><font color=\"red\">". $_SESSION['front_message'] . "</font></p></center>";
	   unset($_SESSION['front_message']);
	}
 ?>

      </div>
      <div class="ui-layout-west">
        <br>
      <div id="combo"></div>
        <br></br>
        <FORM>
         <INPUT type="button" value="University of Albany" style = "background-color:#ddd; width:130px;border-radius:40px/24px;" onclick="myMap.center()">
         <br></br>
         <INPUT type="button"  value="Change Password" style = "background-color:#ddd; width:130px;border-radius:40px/24px;" onClick="location.href='changepassword.html'">
         <br></br>
         <INPUT type="button" value="Logout" style = "background-color:#ddd; width:130px;border-radius:40px/24px;" onClick="location.href='logout.php'">
        </FORM>
      </div>
      <div class="ui-layout-center">
        <div id="map_canvas" style="width: 100%; height: 100%"></div>
     </div>
      <div class="ui-layout-south" style="background-color:#C3C5CA;">
      </br>
        <label for="user"><b>STATUS:&nbsp;&nbsp;</b></label><input type="text" style="border: 1px solid #000;" id="status" size="100"/>&nbsp;&nbsp;<input name="text" type="button"  style ="background-color:#ddd; width:130px;border-radius:40px/24px;" onclick="updateStatus(this)" value="Update"/>
      </div>
  </body>
</html>
         
      
     
 
 
 
 
 
 
 
 
 
 
 
 
 
 
  