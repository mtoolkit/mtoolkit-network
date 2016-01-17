<?php
namespace mtoolkit\network;

/*
 * This file is part of MToolkit.
 *
 * MToolkit is free software: you can redistribute it and/or modify
 * it under the terms of the LGNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * MToolkit is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * LGNU Lesser General Public License for more details.
 *
 * You should have received a copy of the LGNU Lesser General Public License
 * along with MToolkit.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @author  Michele Pagnin
 */

class MNetworkRequest
{
    /**
     * @var MUrl
     */
    private $url;
    
    public function __construct( MUrl $url )
    {
        $this->url=$url;
    }
    
    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl( MUrl $url )
    {
        $this->url = $url;
        return $this;
    }
    
//    QVariant	attribute(Attribute code, const QVariant & defaultValue = QVariant()) const
//    bool	hasRawHeader(const QByteArray & headerName) const
//    QVariant	header(KnownHeaders header) const
//    QObject *	originatingObject() const
//    Priority	priority() const
//    QByteArray	rawHeader(const QByteArray & headerName) const
//    QList<QByteArray>	rawHeaderList() const
//    void	setAttribute(Attribute code, const QVariant & value)
//    void	setHeader(KnownHeaders header, const QVariant & value)
//    void	setOriginatingObject(QObject * object)
//    void	setPriority(Priority priority)
//    void	setRawHeader(const QByteArray & headerName, const QByteArray & headerValue)
//    void	setSslConfiguration(const QSslConfiguration & config)
//    QSslConfiguration	sslConfiguration() const
//    void	swap(QNetworkRequest & other)
}

final class KnownHeaders
{
    /**
     * Corresponds to the HTTP Content-Disposition header and contains a string 
     * containing the disposition type (for instance, attachment) and a 
     * parameter (for instance, filename).
     */
    const ContentDispositionHeader=6;
    
    /**
     * Corresponds to the HTTP Content-Type header and contains a string 
     * containing the media (MIME) type and any auxiliary data (for instance, 
     * charset).
     */
    const ContentTypeHeader=0;	
    
    /**
     * Corresponds to the HTTP Content-Length header and contains the length in 
     * bytes of the data transmitted.
     */
    const ContentLengthHeader=1;	
    
    /**
     * Corresponds to the HTTP Location header and contains a URL representing 
     * the actual location of the data, including the destination URL in case of 
     * redirections.
     */
    const LocationHeader=2;	
    
    /**
     * Corresponds to the HTTP Last-Modified header and contains a DateTime 
     * representing the last modification date of the contents.
     */
    const LastModifiedHeader=3;	
    
    /**
     * Corresponds to the HTTP Cookie header and contains a MList<MNetworkCookie> 
     * representing the cookies to be sent back to the server.
     */
    const CookieHeader=4;	
    
    /**
     * Corresponds to the HTTP Set-Cookie header and contains a MList<MNetworkCookie> 
     * representing the cookies sent by the server to be stored locally.
     */
    const SetCookieHeader=5;	
    
    /**
     * The User-Agent header sent by HTTP clients.
     */
    const UserAgentHeader=7;	
    
    /**
     * The Server header received by HTTP clients.
     */
    const ServerHeader=8;	
}