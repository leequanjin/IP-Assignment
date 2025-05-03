<?xml version="1.0" encoding="UTF-8"?>
<!-- Author     : Lee Quan Jin -->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />

    <xsl:template match="/categories">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Category Title</th>
                    <th scope="col" class="text-center">Delete</th>
                </tr>
            </thead>
            <tbody>
                <xsl:for-each select="category">
                    <tr>
                        <td class="text-center">
                            <xsl:value-of select="title"/>
                        </td>
                        <td class="text-center">
                            <a class="text-dark">
                                <xsl:attribute name="href">
                                    <xsl:text>adminIndex.php?module=category&amp;action=view&amp;delete_category=</xsl:text>
                                    <xsl:value-of select="title"/>
                                </xsl:attribute>
                                <xsl:attribute name="onclick">
                                    <xsl:text>return confirm('Are you sure you want to delete the category: </xsl:text>
                                    <xsl:value-of select="title"/>
                                    <xsl:text>?')</xsl:text>
                                </xsl:attribute>
                                <i class="fa-solid fa-trash fa-lg"></i>
                            </a>
                        </td>
                    </tr>
                </xsl:for-each>
            </tbody>
        </table>
    </xsl:template>
</xsl:stylesheet>