<?php
namespace mtoolkit\network\http;

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

use mtoolkit\core\MObject;

class MHttpRequest extends MObject
{
    /**
     * @var MHttpHeaders
     */
    private $headers = null;

    /**
     * @var string
     */
    private $httpRequestMethod = HttpRequestMethod::GET;

    /**
     * @var string
     */
    private $url = null;
    private $post = array();
    private $cookie = array();

    /**
     * @param string $url
     * @param \MToolkit\Core\MObject $parent
     */
    public function __construct( $url = null, MObject $parent = null )
    {
        parent::__construct( $parent );

        $this->url = $url;
        $this->headers = new MHttpHeaders();
    }

    public function addPost( $key, $value )
    {
        $this->post[$key] = $value;
    }

    public function addCookie( $key, $value )
    {
        $this->cookie[$key] = $value;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return \MToolkit\Network\HTTP\MHttpRequest
     */
    public function setUrl( $url )
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return HttpRequestMethod
     */
    public function getHttpRequestMethod()
    {
        return $this->httpRequestMethod;
    }

    /**
     * @param $httpRequestMethod
     * @return $this
     * @throws \Exception
     */
    public function setHttpRequestMethod( $httpRequestMethod )
    {
        if ( $httpRequestMethod != HttpRequestMethod::GET && $httpRequestMethod != HttpRequestMethod::POST )
        {
            throw new \Exception( 'Invalid value for $httpRequestMethod, only "GET" or "POST" are valid values.' );
        }

        $this->httpRequestMethod = $httpRequestMethod;
        return $this;
    }

    /**
     * @return MHttpHeaders
     */
    public function &getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders( MHttpHeaders $headers )
    {
        $this->headers = $headers;
        return $this;
    }

    public function isPostRequest()
    {
        return ( $this->httpRequestMethod == HttpRequestMethod::POST );
    }

    public function isGetRequest()
    {
        return ( $this->httpRequestMethod == HttpRequestMethod::GET );
    }

    /**
     * @return MHttpResponse
     */
    public function run()
    {
        // Add cookies to header
        if ( count( $this->cookie ) > 0 )
        {
            $cookies = '';

            foreach ( $this->cookie as $key => $value )
            {
                $cookies.=sprintf( '%s=%s; ', $key, $value );
            }
            
            $this->headers->add('Cookie', $cookies);
        }

        $options = array(
            'http' => array(
                'header' => (string) $this->headers,
                'method' => $this->httpRequestMethod,
                'content' => http_build_query( $this->post ),
            ),
        );
        
        $context = stream_context_create( $options );
        
        $response=new MHttpResponse($this);
        {
            $result = file_get_contents( $this->url, false, $context );
            
            $response->setVersion($http_response_header[0]);
            
            for( $i=1; $i<count( $http_response_header ); $i++ )
            {
                $currentHeader=$http_response_header[$i];
                list($key, $value)=  explode(":", $currentHeader, 2);
                $response->getHeaders()->add($key, $value);
            }
            
            $response->setBody($result);
        }
        
        return $response;
    }

}

final class HttpRequestMethod
{
    const POST = 'post';
    const GET = 'get';

}