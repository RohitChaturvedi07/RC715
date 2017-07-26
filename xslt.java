package xslt;

import java.awt.Desktop;
import javax.xml.parsers.*;
import org.w3c.dom.*;
import javax.xml.transform.*;
import javax.xml.transform.dom.*;
import javax.xml.transform.stream.*;
import java.io.*;
import org.xml.sax.*;
import java.net.*;
import java.util.*;


public class xslt { 
  public static void main (String[] args) throws MalformedURLException, TransformerConfigurationException, ParserConfigurationException, SAXException, IOException, TransformerException, Exception
  {
    System.out.println("Enter keyword: ");
    Scanner scanner = new Scanner(System.in);
    String keyword = scanner.next();
    URL url = new URL("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&categoryId=72&keyword="+keyword+"&numItems=20");
    URLConnection conn = url.openConnection();
    DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
    DocumentBuilder builder = factory.newDocumentBuilder();
    Document doc = builder.parse(conn.getInputStream());
    Source source = new DOMSource(doc);
    StreamSource styleSource = new StreamSource("search.xsl");
    StreamResult result = new StreamResult("sample.html");
    Transformer xformer = TransformerFactory.newInstance().newTransformer(styleSource);
    xformer.transform(source, result);
    String url1="sample.html";
    File htmlFile = new File(url1);
    Desktop.getDesktop().browse(htmlFile.toURI());
  }
}
