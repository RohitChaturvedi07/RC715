package xpath;

import java.util.*;
import java.io.*;
import static java.lang.Integer.parseInt;
import java.net.*;
import javax.xml.parsers.*;
import javax.xml.transform.*;
import org.xml.sax.*;
import javax.xml.xpath.*;
import org.w3c.dom.*;


public class xpath {
  public static void main (String[] args) throws MalformedURLException, ParserConfigurationException, SAXException, IOException, TransformerException, Exception
  {
    System.out.println("Enter keyword: ");
    Scanner scanner = new Scanner(System.in);
    String key = scanner.next();
    URL url = new URL("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&categoryId=72&keyword="+key+"&numItems=20");
    URLConnection conn = url.openConnection();
    DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
    DocumentBuilder builder = factory.newDocumentBuilder();
    Document doc = builder.parse(conn.getInputStream());
    System.out.println("select one of the options \n 1. Full Description of products with Rating >= 4.5 \n 2. Name & Min price of products with name containing word Sony \n 3. Name of products with name containing Sony and $1000<=Price<=$2000");
    int opt = parseInt(scanner.next());
    if(opt==1)
        eval("/GeneralSearchResponse/categories/category/items/product[rating/rating>=4.5]/fullDescription",doc);
    else if(opt==2)
        eval("/GeneralSearchResponse/categories/category/items/product[contains(name,'Sony')]/name | /GeneralSearchResponse/categories/category/items/product[contains(name,'Sony')]/minPrice",doc);
    else if(opt==3)
        eval("/GeneralSearchResponse/categories/category/items/product[contains(name,'Sony') and minPrice>=1000 and minPrice<=2000]/name",doc);
    else
        System.out.print("Wrong Option");
}
    static void print ( Node e ) {
	if (e instanceof Text)
	    System.out.print(((Text) e).getData());
	else {
	    NodeList c = e.getChildNodes();
	    System.out.print("<"+e.getNodeName());
	    NamedNodeMap attributes = e.getAttributes();
	    for (int i = 0; i < attributes.getLength(); i++)
		System.out.print(" "+attributes.item(i).getNodeName()
				 +"=\""+attributes.item(i).getNodeValue()+"\"");
	    System.out.print(">");
	    for (int k = 0; k < c.getLength(); k++)
		print(c.item(k));
	    System.out.print("</"+e.getNodeName()+">");
	}
    }
    static void eval ( String query,Document doc) throws Exception {
	XPathFactory xpathFactory = XPathFactory.newInstance();
	XPath xpath = xpathFactory.newXPath();
	XPathExpression expr = xpath.compile(query);
        NodeList nl = (NodeList) expr.evaluate(doc, XPathConstants.NODESET);
        System.out.println(nl.getLength());
	for (int i = 0; i < nl.getLength(); i++)
        {print(nl.item(i));
	System.out.println();}
    } 
}