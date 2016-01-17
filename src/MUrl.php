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
use mtoolkit\core\MString;

/**
 * The MUrl class provides a convenient interface for working with URLs. <br />
 * It can parse and construct URLs in both encoded and unencoded form. MUrl also 
 * has support for internationalized domain names (IDNs). <br />
 * The most common way to use MUrl is to initialize it via the constructor by 
 * passing a QString. Otherwise, setUrl() can also be used.<br />
 * <br />
 * Examples:
 * <ul>
 *      <li>
 *          <i>ftp://user:password@ftp.micene.net:6969</i>
 *          <br />
 *          <ul>
 *              <li>ftp: scheme</li>
 *              <li>user: username</li>
 *              <li>password: password</li>
 *              <li>user:password: user info</li>
 *              <li>ftp.micene.net: host</li>
 *              <li>6969: port</li>
 *              <li>user:password@ftp.micene.net:6969: authority</li>
 *          </ul>
 *      </li>
 *      <li>
 *          <i>http://www.micene.net/folder01/folder02/index.html?key=value#fragment</i>
 *          <br />
 *          <ul>
 *              <li>http: scheme</li>
 *              <li>/folder01/folder02/: path</li>
 *              <li>key=value: query</li>
 *              <li>#fragment: fragment</li>
 *          </ul>
 *      </li>
 *      <li>
 *          <i>mailto:info@micene.net</i>
 *          <br />
 *          <ul>
 *              <li>mailto: scheme</li>
 *              <li>info@micene.net: path</li>
 *          </ul>
 *      </li>
 * </ul>
 */
class MUrl
{
    private $scheme = null;
    private $host = null;
    private $user = null;
    private $password = null;
    private $path = null;
    private $query = null;
    private $fragment = null;
    private $port = null;

    /**
     * Constructs a URL by parsing url. MUrl will automatically percent encode 
     * all characters that are not allowed in a URL and decode the 
     * percent-encoded sequences that represent a character that is allowed in a
     * URL.
     * 
     * @param string|null $url
     */
    public function __construct( $url = null )
    {
        $this->setUrl( $url );
    }

//QString authority(ComponentFormattingOptions options = PrettyDecoded) const

    /**
     * Resets the content of the MUrl. After calling this function, the MUrl is 
     * equal to one that has been constructed with the default empty constructor.
     */
    public function clear()
    {
        $this->scheme = null;
        $this->host = null;
        $this->user = null;
        $this->password = null;
        $this->path = null;
        $this->query = null;
        $this->fragment = null;
        $this->port = null;
    }

//QString errorString() const
//QString fragment(ComponentFormattingOptions options = PrettyDecoded) const

    /**
     * Returns true if this URL contains a fragment (i.e., if # was seen on it).
     * 
     * @return boolean
     */
    public function hasFragment()
    {
        return ($this->fragment!=null);
    }

    /**
     * Returns true if this URL contains a Query (i.e., if ? was seen on it).
     * 
     * @return boolean
     */
    public function hasQuery()
    {
        return ($this->query!=null);
    }

    /**
     * Returns the host of the URL if it is defined; otherwise an empty string 
     * is returned.
     * 
     * @return string|null
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Returns true if the URL has no data; otherwise returns false.
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return ($this->getUrl()==MString::EMPTY_STRING);
    }

//bool isLocalFile() const
//bool isParentOf(const MUrl & childUrl) const
//bool isRelative() const
//bool isValid() const

    /**
     * Returns the password of the URL if it is defined; otherwise an empty 
     * string is returned.
     * 
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the path of the URL.
     * 
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the port of the URL, or defaultPort if the port is unspecified.
     * 
     * @param int $defaultPort
     * @return int|null
     */
    public function getPort( $defaultPort = -1 )
    {
        if( $this->port!=null )
        {
            return (int) $this->port;
        }

        return $defaultPort;
    }

    /**
     * Returns the query string of the URL if there's a query string, or an 
     * empty result if not. To determine if the parsed URL contained a query 
     * string, use hasQuery().
     * 
     * @return string|null
     */
    public function getQuery()
    {
        return $this->query;
    }

//MUrl resolved(const MUrl & relative) const

    /**
     * Returns the scheme of the URL. If an empty string is returned, this means 
     * the scheme is undefined and the URL is then relative.
     * The scheme can only contain US-ASCII letters or digits, which means it cannot 
     * contain any character that would otherwise require encoding.
     * 
     * @return string|null
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * 
     * @param string|null $authority
     * @return \MToolkit\Network\MUrl
     */
    public function setAuthority( $authority )
    {
        if( $authority==null )
        {
            $this->host = null;
            $this->user = null;
            $this->password = null;
            $this->port = null;
        
            return $this;
        }
        
        $urlParts = parse_url( $authority );
        
        $this->host = $urlParts['host'];
        $this->user = $urlParts['user'];
        $this->password = $urlParts['pass'];
        $this->port = $urlParts['port'];

        return $this;
    }

    /**
     * Sets the fragment of the URL to fragment. 
     * 
     * @param string|null $fragment
     * @return \MToolkit\Network\MUrl
     */
    public function setFragment( $fragment )
    {
        $this->fragment = $fragment;
        return $this;
    }

    /**
     * Sets the host of the URL to host.
     * 
     * @param string|null $host
     * @return \MToolkit\Network\MUrl
     */
    public function setHost( $host )
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Sets the URL's password to password. 
     * 
     * @param string|null $password
     * @return \MToolkit\Network\MUrl
     */
    public function setPassword( $password )
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Sets the path of the URL to path.
     * 
     * @param string|null $path
     * @return \MToolkit\Network\MUrl
     */
    public function setPath( $path )
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Sets the port of the URL to port. 
     * 
     * @param string|null $port
     * @return \MToolkit\Network\MUrl
     */
    public function setPort( $port )
    {
        $this->port = $port;
        return $this;
    }

    /**
     * Sets the query string of the URL to query.
     * 
     * @param string|null $query
     * @return \MToolkit\Network\MUrl
     */
    public function setQuery( $query )
    {
        $this->query = $query;
        return $this;
    }

//void setQuery(const MUrlQuery & query)

    /**
     * Sets the scheme of the URL to scheme. 
     * 
     * @param string|null $scheme
     * @return \MToolkit\Network\MUrl
     */
    public function setScheme( $scheme )
    {
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * Parses url and sets this object to that value.
     *
     * @param string|null $url
     * @return MUrl|null
     */
    public function setUrl( $url )
    {
        if( $url==null )
        {
            return null;
        }

        $urlParts = parse_url( $url );

        $this->scheme = $urlParts['scheme'];
        $this->host = $urlParts['host'];
        $this->user = $urlParts['user'];
        $this->password = $urlParts['pass'];
        $this->path = $urlParts['path'];
        $this->query = $urlParts['query'];
        $this->fragment = $urlParts['fragment'];
        $this->port = $urlParts['port'];

        return $this;
    }

    /**
     * Sets the user info of the URL to userInfo.
     * 
     * @param string|null $userInfo
     * @return \MToolkit\Network\MUrl
     */
    public function setUserInfo( $userInfo )
    {
        $userInfoParts = explode( ":", $userInfo );
        $this->user = $userInfoParts[0];
        $this->password = $userInfoParts[1];

        return $this;
    }

    /**
     * Sets the URL's user name to userName.
     * 
     * @param string|null $userName
     * @return \MToolkit\Network\MUrl
     */
    public function setUserName( $userName )
    {
        $this->user = $userName;
        return $this;
    }

//void swap(MUrl & other)
//QString toDisplayString(FormattingOptions options = FormattingOptions( PrettyDecoded )) const
//QByteArray toEncoded(FormattingOptions options = FullyEncoded) const
//QString toLocalFile() const
//QString toString(FormattingOptions options = FormattingOptions( PrettyDecoded )) const
//QString topLevelDomain(ComponentFormattingOptions options = PrettyDecoded) const

    /**
     * Returns a string representation of the URL. 
     * 
     * @return string|null
     */
    public function getUrl()
    {
        $scheme = $this->scheme!=null ? $this->scheme . '://' : MString::EMPTY_STRING;
        $host = $this->host!=null ? $this->host : MString::EMPTY_STRING;
        $port = $this->port!=null ? ':' . $this->port : MString::EMPTY_STRING;
        $user = $this->user!=null ? $this->user : MString::EMPTY_STRING;
        $pass = $this->password!=null ? ':' . $this->password : MString::EMPTY_STRING;
        $pass = ($user||$pass) ? "$pass@" : MString::EMPTY_STRING;
        $path = $this->path!=null ? $this->path : MString::EMPTY_STRING;
        $query = $this->query!=null ? '?' . $this->query : MString::EMPTY_STRING;
        $fragment = $this->fragment!=null ? '#' . $this->fragment : MString::EMPTY_STRING;

        return $scheme . $user . $pass . $host . $port . $path . $query . $fragment;
    }

    /**
     * Returns the user info of the URL, or an empty string if the user info is 
     * undefined.
     * 
     * @return string|null
     */
    public function getUserInfo()
    {
        return ( MString::isNullOrEmpty( $this->user ) ? MString::EMPTY_STRING : $this->user )
                . ( MString::isNullOrEmpty( $this->password ) ? MString::EMPTY_STRING : ':' . $this->password );
    }

    /**
     * Returns the user name of the URL if it is defined; otherwise an empty 
     * string is returned.
     * 
     * @return string|null
     */
    public function getUserName()
    {
        return $this->user;
    }

    public function __toString()
    {
        return $this->getUrl();
    }

}

