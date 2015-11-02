<?php

namespace Rmasters\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class AssemblaResourceOwner implements ResourceOwnerInterface
{
    /** @var array */
    protected $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        return $this->response['id'];
    }

    public function getLogin()
    {
        return $this->response['login'];
    }

    public function getName()
    {
        return $this->response['name'];
    }

    public function getPicture()
    {
        return $this->response['picture'];
    }

    public function getEmail()
    {
        return $this->response['email'];
    }

    public function getOrganization()
    {
        return $this->response['organization'];
    }

    public function getPhone()
    {
        return $this->response['phone'];
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'login' => $this->getLogin(),
            'name' => $this->getName(),
            'picture' => $this->getPicture(),
            'email' => $this->getEmail(),
            'organization' => $this->getOrganization(),
            'phone' => $this->getPhone(),
        ];
    }
}
