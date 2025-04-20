<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>XML Sitemap</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <style type="text/css">
                    body {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                        color: #333;
                        max-width: 75rem;
                        margin: 0 auto;
                        padding: 2rem;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 2rem 0;
                    }
                    th, td {
                        padding: 1rem;
                        text-align: left;
                        border-bottom: 1px solid #eee;
                    }
                    th {
                        background: #f5f5f5;
                        font-weight: 600;
                    }
                    tr:hover td {
                        background: #f9f9f9;
                    }
                    a {
                        color: #0066cc;
                        text-decoration: none;
                    }
                    a:hover {
                        text-decoration: underline;
                    }
                    .header {
                        margin-bottom: 2rem;
                        border-bottom: 2px solid #eee;
                        padding-bottom: 1rem;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>XML Sitemap</h1>
                    <p>This is the sitemap for the website, listing all accessible pages.</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>URL</th>
                            <th>Last Modified</th>
                            <th>Change Frequency</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="sitemap:urlset/sitemap:url">
                            <tr>
                                <td>
                                    <a href="{sitemap:loc}">
                                        <xsl:value-of select="sitemap:loc"/>
                                    </a>
                                </td>
                                <td>
                                    <xsl:value-of select="sitemap:lastmod"/>
                                </td>
                                <td>
                                    <xsl:value-of select="sitemap:changefreq"/>
                                </td>
                                <td>
                                    <xsl:value-of select="sitemap:priority"/>
                                </td>
                            </tr>
                        </xsl:for-each>
                    </tbody>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>