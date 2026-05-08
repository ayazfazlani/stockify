<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" 
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>XML Sitemap | POS for Shops</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <style type="text/css">
                    body {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
                        color: #1e293b;
                        background-color: #f8fafc;
                        margin: 0;
                        padding: 2rem;
                    }
                    .container {
                        max-width: 1000px;
                        margin: 0 auto;
                        background: #fff;
                        padding: 2rem;
                        border-radius: 1rem;
                        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                    }
                    h1 {
                        color: #10b981;
                        font-weight: 800;
                        margin-bottom: 0.5rem;
                        display: flex;
                        items-center;
                        gap: 0.5rem;
                    }
                    p {
                        color: #64748b;
                        margin-bottom: 2rem;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 0.875rem;
                    }
                    th {
                        text-align: left;
                        padding: 0.75rem 1rem;
                        background-color: #f1f5f9;
                        border-bottom: 2px solid #e2e8f0;
                        color: #475569;
                    }
                    td {
                        padding: 0.75rem 1rem;
                        border-bottom: 1px solid #f1f5f9;
                    }
                    tr:hover td {
                        background-color: #f8fafc;
                    }
                    a {
                        color: #3b82f6;
                        text-decoration: none;
                        font-weight: 500;
                    }
                    a:hover {
                        text-decoration: underline;
                    }
                    .badge {
                        padding: 0.25rem 0.5rem;
                        border-radius: 9999px;
                        font-size: 0.75rem;
                        font-weight: 600;
                    }
                    .badge-green { background: #dcfce7; color: #166534; }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>POS for Shops Sitemap</h1>
                    <p>This is a XML Sitemap intended for consumption by search engines. It lists localized URLs for maximum SEO reach.</p>
                    <table>
                        <thead>
                            <tr>
                                <th>URL</th>
                                <th>Priority</th>
                                <th>Change Freq</th>
                                <th>Last Modified</th>
                            </tr>
                        </thead>
                        <tbody>
                            <xsl:for-each select="sitemap:urlset/sitemap:url">
                                <tr>
                                    <td>
                                        <xsl:variable name="itemURL">
                                            <xsl:value-of select="sitemap:loc"/>
                                        </xsl:variable>
                                        <a href="{$itemURL}">
                                            <xsl:value-of select="sitemap:loc"/>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-green">
                                            <xsl:value-of select="sitemap:priority"/>
                                        </span>
                                    </td>
                                    <td>
                                        <xsl:value-of select="sitemap:changefreq"/>
                                    </td>
                                    <td>
                                        <xsl:value-of select="concat(substring(sitemap:lastmod,0,11),concat(' ', substring(sitemap:lastmod,12,5)))"/>
                                    </td>
                                </tr>
                            </xsl:for-each>
                        </tbody>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
