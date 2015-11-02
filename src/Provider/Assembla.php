<?php

namespace Rmasters\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Grant\AbstractGrant;
use Psr\Http\Message\ResponseInterface;

class Assembla extends AbstractProvider
{
    public function getBaseAuthorizationUrl()
    {
        return "https://api.assembla.com/authorization";
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return sprintf("https://%s:%s@api.assembla.com/token", $params['client_id'], $params['client_secret']);
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return "https://api.assembla.com/v1/user.json";
    }

    protected function getDefaultScopes()
    {
        return [];
    }

    protected function getAuthorizationParameters(array $options)
    {
        return array_intersect_key(
            parent::getAuthorizationParameters($options),
            array_flip(['client_id', 'response_type'])
        );
    }

    protected function getAuthorizationHeaders($token = null)
    {
        if (!is_null($token) && $token instanceof AccessToken) {
            return [
                'Authorization' => sprintf('Bearer %s', $token->getToken())
            ];
        };

        return [];
    }

    protected function getAccessTokenUrl(array $params)
    {
        $url = $this->getBaseAccessTokenUrl($params);
        $query = $this->getAccessTokenQuery($params);
        return $this->appendQuery($url, $query);
    }

    protected function getAccessTokenQuery(array $options)
    {
        return parent::getAccessTokenQuery(
            array_intersect_key($options, array_flip(['grant_type', 'code']))
        );
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        return substr((string) $response->getStatusCode(), 0, 1) == '2';
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new AssemblaResourceOwner($response);
    }

    protected function createAccessToken(array $response, AbstractGrant $grant)
    {
        // Double AccessToken creation since Assembla doesn't send the resource
        // owner id in the access token response
        $token = new AccessToken($response);
        $resourceOwner = $this->getResourceOwner($token);
        $response['resource_owner_id'] = $resourceOwner->getId();

        return new AccessToken($response);
    }
}
