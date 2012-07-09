<?php
/**
 * HTTP Basic Authentication Enhanced
 *
 * Use this middleware with your Slim Framework application
 * to require HTTP basic auth for all routes and do Authorization
 * based on Route Parameters equals Username.
 *
 * Based on work and/ or extending classes 'Middleware_Auth_HttpBasic' 
 * and 'Middleware_Auth_Strong' from Josh Lockhart and Andrew Smith's
 * project 'slim/extras', http://github.com/codeguy/Slim-Extras.
 *
 * @author Matthias Geisler <matthias@semanticdreamer.com>
 * @version 1.0
 * @copyright 2012 Josh Lockhart
 * @copyright 2012 Andrew Smith
 * @copyright 2012 Matthias Geisler
 *
 * USAGE
 *
 * $app = new Slim();
 * $app->add(new Slim_Middleware_Auth_HttpBasicWithAuthZ(
 *      array(
 *          'username1' => array(
 *              'password' =>'password1',
 *              'authorized_urls' => array(
 *                   '/accounts/username1/(.*)',
 *                   '/about/.+'
 *              )
 *          ),
 *          'username2' => array(
 *              'password' =>'password2',
 *              'authorized_urls' => array(
 *                   '/accounts/username2/(.*)',
 *                   '/about/.+'
 *              )
 *          ),
 *          'public_urls' => array(
 *                '/'
 *           ),
 *          'login_url' => '/login/'
 *          'loggedin_url' => '/loggedin/'
 *      ),   
 *     'Protected Area'
 * ));
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
class Slim_Middleware_Auth_HttpBasicWithAuthZ extends Slim_Middleware {
    /**
     * @var string
     */
    protected $realm;

    /**
     * @var array
     */
    protected $config = array();

    /**
     * Constructor
     *
     * @param   string  $realm   The HTTP Authentication realm
     * @param   array  $config   Configuration for Credentials and Authorized Urls
     * @return  void
     */
    public function __construct( array $config = array(), $realm = 'Protected Area') {
        $this->realm = $realm;
        $this->config = $config;
    }

    /**
     * Call
     *
     * This method will check the HTTP request headers for previous authentication.
     * If the request has already been authenticated and the request is authorized 
     * the next middleware is called.
     * 
     * In case of no authentication, a 401 Unauthorized response is returned to the client.
     * In case of no authorization, a 403 Forbidden response is returned to the client.
     *
     * @return void
     */
    public function call() {
        $app = $this->app;
        $config = $this->config;
        $realm = $this->realm;

        $this->app->hook('slim.before.router', function () use ($app, $config, $realm) {
          $req = $app->request();
          $res = $app->response();

          $authUser = $req->headers('PHP_AUTH_USER');
          $authPass = $req->headers('PHP_AUTH_PW');

          $login_url = isset($config['login_url']) ? $config['login_url'] : '/login/';
          $loggedin_url = isset($config['loggedin_url']) ? $config['loggedin_url'] : '/loggedin/';

          $public_access = false; //default
          $authorized = false; //default

          $public_urls = isset($config['public_urls']) ? $config['public_urls'] : array();
          //request for public url?
          $public_access = Slim_Middleware_Auth_HttpBasicWithAuthZ::isPathInfoInUrls($req->getPathInfo(), $public_urls);
          if ( $public_access ) { 
            //public access
          }
          elseif ( $authUser && $authPass && array_key_exists($authUser, $config) && $authPass === $config[$authUser]['password'] ) {
              //valid user and pwd
              //request for authorized url?
              $authorized_urls = isset($config[$authUser]['authorized_urls']) ? $config[$authUser]['authorized_urls'] : array();
              array_push($authorized_urls, $loggedin_url.'(.*)'); //add loggedin url
              $authorized = Slim_Middleware_Auth_HttpBasicWithAuthZ::isPathInfoInUrls($req->getPathInfo(), $authorized_urls);
              
              if (!$authorized && $req->getPathInfo() === $login_url ) {
                //not authorized, but request for login url
                //redirect to login success route 'loggedin'
                $app->redirect($loggedin_url.$authUser.'/');
              }
              elseif (!$authorized) {
                //not authorized (403)
                $app->halt(
                  403, 
                  'Forbidden! You don\'t have permission to access \''
                  .$req->getPathInfo()
                  .'\'.'
                  );
              }
          } else {
              //not authenticated (401)
              $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $realm));
              $app->halt(
                401,
                'Access denied! The request for \''
                .$req->getPathInfo()
                .'\' requires valid user authentication.'
                );
          }
        });

        //authenticated and authorized
        $this->next->call();
    }

    public static function isPathInfoInUrls($pathInfo, $urls = array()) {
      foreach ($urls as $surl) {
          $patternAsRegex = $surl;
          if (substr($surl, -1) === '/') {
              $patternAsRegex = $patternAsRegex . '?';
          }
          $patternAsRegex = '@^' . $patternAsRegex . '$@';
          if (preg_match($patternAsRegex, $pathInfo)) {
            return true;
          }
      }
    }
}