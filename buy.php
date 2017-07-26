<?php
session_start();
$bill=0;
?>
<html>
<head><title>shopping cart</title></head>
<body>
<?php
if(isset($_GET['buy'])){
	buy1();
}
if(isset($_GET['del'])){
	del1();
}
if(isset($_GET['EC'])){
	session_unset();
	session_destroy();
	session_start();
}
if(!empty($_SESSION['shop'])){
	foreach($_SESSION['shop'] as $add)
	{
		echo "<table border=1 bgcolor=#DCDCDC>";
		echo "<tr><td><a href =".$add[0]."><img  src =".$add[2]." ></img></a></td><td>".$add[1]."</td><td>".$add[3]."</td><td><a href='buy.php?del=".$add[4]."'> delete</td></tr>";
		$bill=$bill+$add[3];
	}
	echo "</table>";
}
echo "Welcome to the Shopping Basket: </br> Total billing amount: $bill";
echo '<form action="buy.php" method="GET"> <input type="submit" name="EC" value="Empty Cart"/>';
$xmlstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=72&showAllDescendants=true');
$xml = new SimpleXMLElement($xmlstr);
list_print($xml);
if (isset($_GET['search'])){
	search1();
}
function buy1(){
	$b=$_GET['buy'];
	$xmlsrt1=file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&productId='.$b);
	$xml1=new SimpleXMLElement($xmlsrt1);
	$result[0]=(String) $xml1->categories->category->items->product->productOffersURL;
	$result[1]=(String) $xml1->categories->category->items->product->name;
	$result[2]= (String)$xml1->categories->category->items->product->images->image->sourceURL;
	$result[3]= (String)$xml1->categories->category->items->product->minPrice;	
	$result[4]= (String)$_GET['buy'];
	$_SESSION['shop'][$result[4]]=$result;
}
function del1(){
	unset($_SESSION['shop'][$_GET['del']]);
}
function search1(){
	$s=urlencode($_GET['txt_search']);
	$list=$_GET["cat1"];
	$xmlstr2 = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&categoryId='.$list.'&keyword='.$s.'&numItems=20');
	$xml2 = new SimpleXMLElement($xmlstr2);
	if($xml2->categories->category->items)
	{
	foreach($xml2->categories->category->items as $show_list)
	{
		foreach ($show_list->product as $pr)
		{
			$url=$pr->attributes();
			echo "<table border=1 bgcolor=#DCDCDC >";
			echo'<tr> <td><a href="buy.php?buy='.$url.'"><img src = '.$pr->images->image->sourceURL.'></img></a></td> <td>'.$pr->name.'</td> <td>'.$pr->minPrice.'</td> <td>'.$pr->fullDescription.'</td> </tr>';
		}
		echo '</table>';
	}
	}
}
function list_print($xml){
echo '<select name="cat1">';
echo '<option value='.$xml->category->attributes().'>'.$xml->category->name.'</option>';
echo "<optgroup label=".$xml->category->name.":>";
foreach($xml->category->categories->category as $cat)
{
	echo '<option value='.$cat->attributes().'>'.$cat->name.'</option>';
	echo "<optgroup label='$cat->name:'>";
	foreach ($cat->categories->category as $sub)
	{
		echo '<option value="'.$sub->attributes().'">'.$sub->name.'</option>';
	}
	echo '</optgroup>';	
}
echo "</select>";
echo '<label>Search keywords:<input type="text" name="txt_search"/><label> <input type="submit" name="search" value="search"/><br><br>';
echo '</form>';
}
?>
</body>
</html>
