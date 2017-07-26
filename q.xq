let $id:=for $a in doc("auction.xml")/site/categories
return $a/category/@id/string()
for $cat in doc("auction.xml")/site/people/person[profile/interest/@category]
where $cat/profile/interest/@category/string()=$id
group by $id
return ($id, <count>{count($cat/name)}</count>)