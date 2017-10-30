<?php

namespace AppBundle\Security;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator {

    use TargetPathTrait;

    private $em;
    private $router;
    private $passwordEncoder;
    private $csrfTokenManager;
    private $authchecker;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, EntityManagerInterface $em, RouterInterface $router, UserPasswordEncoderInterface $passwordEncoder, AuthorizationChecker $authChecker) {
        $this->em = $em;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->authchecker = $authChecker;
    }

    public function getCredentials(Request $request) {
        $isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');
        if(!$isLoginSubmit){
            return;
        }

        $email = $request->request->get("_username");
        $password = $request->request->get("_password");
        $csrfToken = $request->request->get("_csrf_token");

        if(false === $this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrfToken))) {
            throw new InvalidCsrfTokenException('invalid token!');
        }
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $email
        );

        return [
            "email" => $email,
            "password" => $password,
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        $email = $credentials['email'];
        return $this->em->getRepository('AppBundle:User')
            ->findOneBy(['email' => $email]);
    }

    public function checkCredentials($credentials, UserInterface $user) {
        $password = $credentials['password'];

        if($this->passwordEncoder->isPasswordValid($user, $password)){
            return true;
        }
        return false;
    }

    protected function getLoginUrl() {
        return $this->router->generate('fos_user_security_login');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        if($this->authchecker->isGranted("ROLE_DIRECTOR")) {
            return new RedirectResponse($this->router->generate('easyadmin'));
        }
        return new RedirectResponse($this->router->generate('user_home'));
    }

    protected function getDefaultSuccessRedirectUrl() {
        if($this->authchecker->isGranted("ROLE_DIRECTOR")) {
            return $this->router->generate('easyadmin');
        }
        return $this->router->generate('homepage');
    }
}
