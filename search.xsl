<xsl:stylesheet version="2.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <body>
	<h1>Ebay Search Forum</h1>
<h2>List of Items</h2><p/>
<table border="1"  bgcolor="yellow">
<tr bgcolor="green"><th>ID</th><th>Image URL</th><th>Name</th><th>Min Price</th>
<th>Full Descripion</th></tr>
<xsl:for-each select="GeneralSearchResponse/categories/category/items/product">
    <tr>
	<td><xsl:value-of select="@id"/></td>
        <td><xsl:value-of select="images/image/sourceURL"/></td>
        <td><xsl:value-of select="name"/></td>
        <td><xsl:value-of select="minPrice"/></td>
        <td><xsl:value-of select="fullDescription"/></td>
    </tr>
	  </xsl:for-each>
	</table>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>