<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:u="http://www.sitemaps.org/schemas/sitemap/0.9">
	<xsl:template match="/">
		<html>
			<head>
				<title>Sitemap</title>
			</head>
			<body>
				<xsl:for-each select="u:urlset/u:url">
					<xsl:variable name="url"><xsl:value-of select="u:loc" /></xsl:variable>
					<p><span><xsl:value-of select="u:lastmod" /> - </span><a href="{$url}"><xsl:value-of select="u:loc"/></a></p>
				</xsl:for-each>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>