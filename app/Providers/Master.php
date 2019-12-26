<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Firebase\JWT\JWT;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use App\Models\Token;
use Carbon\Carbon;

class Master extends ServiceProvider
{
	protected $http;

    protected $endpoint;

    protected $headers = [];

    protected $body;

    protected $query = [];

    public function __construct(  )
    {
        $this->http = new Client();

        $this->headers = $this->headers();
    }

    public function headers()
    {
        return $this->headers = [];
    }

    public function uri()
    {
        return config( "restapi.uri" ) . $this->endpoint;
    }

    public function setEndpoint( $endpoint = "" )
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function setHeaders( array $headers )
    {
        $this->headers = array_merge( $this->headers(), $headers );

        return $this;
    }

    public function setBody( $body )
    {
        $this->body = $body;

        return $this;
    }

    public function setQuery( array $query )
    {
        $this->query = http_build_query( $query );

        return $this;
    }

    public function setMethod( $method )
    {
        switch ( $method ) {
            case "multipart":
                $methods = [ "method" => "POST", "more_content" => [ [ "name" => "_method", "contents" => "put" ] ] ];
                break;
            default:
                $methods = [ "method" => "PUT" ];
                break;
        }

        return $methods;
    }

    public function get()
    {
        try {
			
            $request  = $this->http->request(
                "GET"
                , $this->uri()
                , [
                    "headers" => $this->headers
                    , "query" => $this->query
                ]
            );
			

            $response = json_decode( $request->getBody(), true );

        } catch ( ClientException $e ) {
            $body = $e->getResponse()->getBody();
            $response = json_decode( $body->getContents(), true );

        } catch ( ServerException $e ) {
            abort( 500 );

        }

        return $response;
    }

    public function post( $type = "json" )
    {
        try {
            $request  = $this->http->request(
                "POST"
                , $this->uri()
                , [
                    "headers" => $this->headers
                    , "query" => $this->query
                    , $type => $this->body
                ]
            );
            $response = json_decode( $request->getBody(), true );

        } catch ( ClientException $e ) {
            $body = $e->getResponse()->getBody();
            $response = json_decode( $body->getContents(), true );

        } catch ( ServerException $e ) {
            abort( 500 );

        }

        return $response;
    }

    public function put( $type = "json" )
    {
        $method = $this->setMethod( $type );
        $body = array_key_exists( "more_content", $method ) ? array_merge( $this->body, $method["more_content"] ) : $this->body;

        try {
            $request  = $this->http->request(
                $method["method"]
                , $this->uri()
                , [
                    "headers" => $this->headers
                    , "query" => $this->query
                    , $type => $body
                ]
            );
            $response = json_decode( $request->getBody(), true );

        } catch ( ClientException $e ) {
            $body = $e->getResponse()->getBody();
            $response = json_decode( $body->getContents(), true );

        } catch ( ServerException $e ) {
            abort( 500 );

        }

        return $response;
    }

    public function deleted()
    {
        try {
            $request  = $this->http->request(
                "DELETE"
                , $this->uri()
                , [
                    "headers" => $this->headers
                    , "query" => $this->query
                    , "json" => $this->body
                ]
            );
            $response = json_decode( $request->getBody(), true );

        } catch ( ClientException $e ) {
            $body = $e->getResponse()->getBody();
            $response = json_decode( $body->getContents(), true );

        } catch ( ServerException $e ) {
            abort( 500 );

        }

        return $response;
    }
	
    static function token()
	{
		$cekToken = Token::whereRaw(' valid_until >= CURDATE() ')->first();
		
		if($cekToken){
			return $cekToken->token;
		
		}else{
			$key = "T4pagri123#";
			$payload = array(
				"USERNAME" => "muhammad.sanjaya",
				"NIK" => "00003045",
				"JOB_CODE" => "ASISTEN LAPANGAN",
				"USER_AUTH_CODE" => "0217",
				"REFFERENCE_ROLE" => "AFD_CODE",
				"USER_ROLE" => "ASISTEN_LAPANGAN",
				"LOCATION_CODE" => "4123V",
			);
			
			$jwt = JWT::encode($payload, $key);
			
			Token::create([
				'token'=>$jwt,
				'valid_until'=>Carbon::now()->addDay(7),
			]);
			
			return $jwt;	
		}
		
		// print_r($jwt);die;	
		// $decoded = JWT::decode( $access_token, $key, array( 'HS256' ) );
		// $decoded = json_decode( json_encode( $decoded ), true );
		// dd($decoded);
	}
}
