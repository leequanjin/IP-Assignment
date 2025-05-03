<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : category_sidebar.xsl.xsl
    Created on : 3 May 2025, 10:00 pm
    Author     : leeda
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />

    <xsl:template match="/">
        <xsl:call-template name="sidebar"/>
    </xsl:template>

    <xsl:template name="sidebar">
        <div class="col-md-2 bg-secondary p-0 mb-4">
            <ul class="navbar-nav me-auto text-center">
                <li style="background-color: #2B3035;" class="nav-item py-2 border-bottom">
                    <h4 class="text-light py-2">Categories</h4>
                </li>
                <xsl:choose>
                    <xsl:when test="count(/categories/category) > 0">
                        <xsl:for-each select="/categories/category">
                            <li class="nav-item bg-secondary py-2 border-bottom">
                                <a class="nav-link text-light">
                                    <xsl:attribute name="href">
                                        <xsl:text>userIndex.php?category=</xsl:text>
                                        <xsl:value-of select="title"/>
                                    </xsl:attribute>
                                    <xsl:value-of select="title"/>
                                </a>
                            </li>
                        </xsl:for-each>
                    </xsl:when>
                    <xsl:otherwise>
                        <li class="nav-item bg-secondary py-2 border-bottom">
                            <a href="#" class="nav-link text-light">No categories available.</a>
                        </li>
                    </xsl:otherwise>
                </xsl:choose>
            </ul>
        </div>
    </xsl:template>
</xsl:stylesheet>
