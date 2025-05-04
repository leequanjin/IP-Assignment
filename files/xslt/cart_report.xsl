<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output method="html" indent="yes"/>

  <xsl:template match="/">
    <html>
    <head>
      <title>Cart Report</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          background-color: #f9f9f9;
          margin: 20px;
        }
        h2 {
          color: #333;
        }
        table {
          border-collapse: collapse;
          width: 100%;
          background-color: #fff;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
          border: 1px solid #ccc;
          padding: 12px;
          text-align: left;
        }
        th {
          background-color: #f2f2f2;
        }
        tr:nth-child(even) {
          background-color: #f9f9f9;
        }
      </style>
    </head>
    <body>
      <h2>Cart Report</h2>
      <table>
        <tr>
          <th>User Email</th>
          <th>Product ID</th>
          <th>Quantity</th>
        </tr>
        <xsl:for-each select="carts/cart">
          <xsl:for-each select="products/product">
            <tr>
              <td><xsl:value-of select="../../userEmail"/></td>
              <td><xsl:value-of select="productId"/></td>
              <td><xsl:value-of select="productQty"/></td>
            </tr>
          </xsl:for-each>
        </xsl:for-each>
      </table>
    </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
