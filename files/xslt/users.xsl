<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : user.xsl.xsl
    Created on : 3 May 2025, 9:00 pm
    Author     : keekeshen
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
    <xsl:output method="html" indent="yes"/>

    <xsl:template match="/users">
        <html>
            <head>
                <title>List of Users</title>
                <style>
                    table {
                    border-collapse: collapse;
                    width: 80%;
                    margin: 20px auto;
                    }
                    th, td {
                    border: 1px solid #ccc;
                    padding: 8px;
                    text-align: center;
                    }
                    th {
                    background-color: #333;
                    color: white;
                    }
                </style>
            </head>
            <body>
                <h2 style="text-align:center;">Registered Users</h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Address</th>
                    </tr>
                    <xsl:for-each select="user">
                        <tr>
                            <td>
                                <xsl:value-of select="name"/>
                            </td>
                            <td>
                                <xsl:value-of select="age"/>
                            </td>
                            <td>
                                <xsl:value-of select="gender"/>
                            </td>
                            <td>
                                <xsl:value-of select="email"/>
                            </td>
                            <td>
                                <xsl:value-of select="address"/>
                            </td>
                        </tr>
                    </xsl:for-each>
                </table>
                <p style="text-align:center; margin-top:20px;">
                    Total Registered Users: <xsl:value-of select="count(user)"/>
                </p>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
