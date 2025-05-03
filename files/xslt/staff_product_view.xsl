<?xml version="1.0" encoding="UTF-8"?>
<!-- Author     : Lee Quan Jin -->


<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />

    <xsl:template match="/products">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Product ID</th>
                    <th scope="col" class="text-center">Product Title</th>
                    <th scope="col" class="text-center">Product Image</th>
                    <th scope="col" class="text-center">Product Price</th>
                    <th scope="col" class="text-center">Available Stock</th>
                    <th scope="col" class="text-center">Edit</th>
                    <th scope="col" class="text-center">Delete</th>
                </tr>
            </thead>
            <tbody>
                <xsl:for-each select="product">
                    <tr>
                        <th scope="row" class="text-center">
                            <xsl:value-of select="id"/>
                        </th>
                        <td class="text-center">
                            <xsl:value-of select="title"/>
                        </td>
                        <td>
                            <img>
                                <xsl:attribute name="src">
                                    <xsl:text>../files/uploads/</xsl:text>
                                    <xsl:value-of select="image"/>
                                </xsl:attribute>
                                <xsl:attribute name="alt">
                                    <xsl:value-of select="title"/>
                                </xsl:attribute>
                                <xsl:attribute name="width">100%</xsl:attribute>
                                <xsl:attribute name="height">150px</xsl:attribute>
                                <xsl:attribute name="style">object-fit: contain;</xsl:attribute>
                            </img>
                        </td>
                        <td class="text-center">
                            RM <xsl:value-of select="format-number(price, '0.00')"/>
                        </td>
                        <td class="text-center">
                            <xsl:value-of select="stock"/>
                        </td>
                        <td class="text-center">
                            <a class="text-dark">
                                <xsl:attribute name="href">
                                    <xsl:text>adminIndex.php?module=product&amp;action=edit&amp;edit_product=</xsl:text>
                                    <xsl:value-of select="id"/>
                                </xsl:attribute>
                                <i class="fa-solid fa-pen-to-square fa-lg"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a class="text-dark">
                                <xsl:attribute name="href">
                                    <xsl:text>adminIndex.php?module=product&amp;action=view&amp;delete_product=</xsl:text>
                                    <xsl:value-of select="id"/>
                                </xsl:attribute>
                                <xsl:attribute name="onclick">
                                    <xsl:text>return confirm('Are you sure you want to delete the product: </xsl:text>
                                    <xsl:value-of select="id"/>
                                    <xsl:text>. </xsl:text>
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
