function initialize () {
	
}
function movieDetails(id)
{
	var xhr1 = new XMLHttpRequest();
	var x=document.getElementById("info1");
   xhr1.open("GET", "proxy.php?method=/3/movie/" + id);
   xhr1.setRequestHeader("Accept","application/json");
   xhr1.onreadystatechange = function () {
       if (this.readyState == 4) {
          var json = JSON.parse(this.responseText);
		  var pp = "https://image.tmdb.org/t/p/w500"+json.poster_path;
		  var original_title= json.original_title;
		  var genres=""
		  for (var i=0;i<json.genres.length;i++)
		  {
		  genres=genres+json.genres[i].name+",";
		  }		
		  var overview=json.overview;
          //var str = JSON.stringify(json,undefined,2); 
		  //x.innerHTML = "<pre>" + str + "</pre>";
          x.innerHTML = '<img src='+pp+'></img>' +"<br>"+ original_title +"<br>"+ genres +"<br>"+ overview+"<br>";
       }
   };
   var xhr2 = new XMLHttpRequest();
   xhr2.open("GET", "proxy.php?method=/3/movie/"+id+"/credits");
   xhr2.setRequestHeader("Accept","application/json");
   xhr2.onreadystatechange = function () {
       if (this.readyState == 4) {
          var json = JSON.parse(this.responseText);
		  for(var i=0;i<5 && i<json.cast.length ;i++)
		{	
			var name = json.cast[i].name;
			x.innerHTML = x.innerHTML + name+"<br>";
		}
          //var str = JSON.stringify(json,undefined,2);
          //x.innerHTML = x.innerHTML+ "<pre>" + str + "</pre>";
       }
   };
   xhr1.send(null);
   xhr2.send(null);
}

function sendRequest () {
   var xhr = new XMLHttpRequest();
   	var x=document.getElementById("output");
   var query = encodeURI(document.getElementById("form-input").value);
   xhr.open("GET", "proxy.php?method=/3/search/movie&query=" + query);
   xhr.setRequestHeader("Accept","application/json");
   xhr.onreadystatechange = function () {
       if (this.readyState == 4) {
          var json = JSON.parse(this.responseText);
		  var count=json.results.length;
          //var str = JSON.stringify(json,undefined,2);		  
          //document.getElementById("output").innerHTML = "<pre>" + str + count + "</pre>";
		for(var i=0;i<count;i++)
		{
			var id = json.results[i].id;
			var original_title = json.results[i].original_title;
			var year= json.results[i].release_date;
			year = year.slice(0,4);
			x.innerHTML = x.innerHTML +"<br>"+ "<a onclick='movieDetails("+id+")' >"+original_title+ "	"+ year+"</a>" ;
		}
	 }
   };
   xhr.send(null);
}
