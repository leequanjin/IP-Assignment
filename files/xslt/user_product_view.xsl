<?xml version="1.0" encoding="UTF-8"?>
<!-- Author     : Lee Quan Jin -->

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:param name="currency" select="'MYR'" />
    <xsl:param name="conversionRate" select="1" />

    <xsl:output method="html" encoding="UTF-8" indent="yes" />

    <xsl:template match="/products">
        <xsl:variable name="lcSearch" select="translate($search, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')" />
        <xsl:variable name="lcCategory" select="translate($category, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')" />
        <xsl:for-each select="product">
            <xsl:variable name="lcTitle" select="translate(title, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')" />
            <xsl:if test="contains($lcTitle, $lcSearch) and (translate(category, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz') = $lcCategory or $category = '')">
                <xsl:apply-templates select="."/>
            </xsl:if>
        </xsl:for-each>
    </xsl:template>


    <xsl:template match="product">
        <div class="col-md-4 mb-4">
            <div class="card" style="width: 18rem;">
                <xsl:variable name="imagePath" select="concat('../files/uploads/', image)" />
                <img>
                    <xsl:attribute name="src">
                        <xsl:value-of select="$imagePath" />
                    </xsl:attribute>
                    <xsl:attribute name="alt">
                        <xsl:value-of select="title" />
                    </xsl:attribute>
                    <xsl:attribute name="class">card-img-top</xsl:attribute>
                    <xsl:attribute name="loading">lazy</xsl:attribute>
                </img>

                <div class="card-body">
                    <h5 class="card-title">
                        <xsl:value-of select="title"/>
                    </h5>
                    <p class="card-text">
                        <xsl:value-of select="description"/>
                    </p>
                    <p class="card-text fw-bold">
                        <xsl:value-of select="$currency"/> 
                        <xsl:value-of select="format-number(price * $conversionRate, '0.00')"/>
                    </p>
                    <a class="btn btn-secondary">
                        <xsl:attribute name="href">
                            <xsl:text>userIndex.php?module=cart&amp;action=add&amp;currency=</xsl:text>
                            <xsl:value-of select="$currency"/>
                            <xsl:text>&amp;add_to_cart=</xsl:text>
                            <xsl:value-of select="id"/>
                        </xsl:attribute>
                        Add to cart
                    </a>
                </div>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>
