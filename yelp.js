function initialize () {
	var LatLng = {lat: 32.75, lng: -97.13};
	map = new google.maps.Map(document.getElementById('map'), {
    center: LatLng,
    zoom: 16
  });	
}
function initMap(lat1,lng1,i) {
	var myLatLng = {lat: lat1, lng: lng1};
	var marker=new google.maps.Marker({
			  position: myLatLng,
			  label: i.toString(),
			  map:map
		  });
}

function sendRequest () {
   var xhr = new XMLHttpRequest();
   var x=document.getElementById("output");
   x.innerHTML ="";
   var bounds = map.getBounds();
   var ne = bounds.getNorthEast(); 
   var sw = bounds.getSouthWest();
   var nela = ne.lat();
   var nelo = ne.lng();
   var swla = sw.lat();
   var swlo = sw.lng();
   var query = encodeURI(document.getElementById("search").value);
   var query = query.replace(" ","+");
   xhr.open("GET", "proxy.php?term="+query+"&bounds="+swla+","+swlo+"|"+nela+","+nelo+"&limit=10");
   xhr.setRequestHeader("Accept","application/json");
   xhr.onreadystatechange = function () {
       if (this.readyState == 4) {
          var json = JSON.parse(this.responseText);
		  var count=json.businesses.length;
		  for(var i=0;i<10 && i<count;i++)
		{
		  var j=i+1;
		  var image_url = json.businesses[i].image_url;
		  var name=json.businesses[i].name;
		  var url=json.businesses[i].url;
		  var rating_img_url=json.businesses[i].rating_img_url;
		  var snippet_text=json.businesses[i].snippet_text;
		  var lat1=json.businesses[i].location.coordinate.latitude;
		  var lng1=json.businesses[i].location.coordinate.longitude;
		  initMap(lat1,lng1,j)
		  x.innerHTML = x.innerHTML +"<br>"+j+'<img src='+image_url+'></img>'+"<br>"+ "<a href="+url+">"+name+"</a>" +"<br>"+'<img src='+rating_img_url+'></img>'+"<br>"+snippet_text;
		}
		  //var str = JSON.stringify(json,undefined,2);
          //document.getElementById("output").innerHTML = "<pre>" + str+ "</pre>";
       }
   };
   xhr.send(null);
}
 //the image "image_url",
 //the "name" as a clickable "url" to the Yelp page of this restaurant,
 //the image "rating_img_url" (1-5 stars),
 //and the "snippet_text"