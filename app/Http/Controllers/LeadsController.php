<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exception\DeletedLeadException;
use App\Http\Controllers\Exception\InvalidTokenException;
use App\Http\Controllers\Exception\InvalidVerificationCodeException;
use App\Http\Controllers\Exception\LeadNotFoundException;
use App\Http\Controllers\Exception\TokenNotFoundException;
use App\Repositories\Query\PartiallySignedUpLead;
use Exception;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Rest\Sales\Application\Lead\FinalizeLeadOnlineSignUpRequest;
use Rest\Sales\Application\Lead\Handler\FinalizedLeadOnlineSignUpHandler;
use Rest\Sales\Application\Lead\Handler\PartiallySignedUpLeadVerificationHandler;
use Rest\Sales\Application\Lead\Handler\PartialLeadOnlineSignUpHandler;
use Rest\Sales\Application\Lead\PartialLeadOnlineSignUpRequest;
use Rest\Sales\Application\Lead\VerifyLeadPartialSignUpRequest;
use Rest\Sales\Domain\Model\Lead\Lead;

class LeadsController extends Controller
{

    /**
     * @param Request $httpRequest
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \ReflectionException
     */
    public function create(Request $httpRequest)
    {
        try {
            $leadId = $this->getLeadId($httpRequest);
            $email = $httpRequest->has('email') ? $httpRequest->get('email') : '';
            $request = new PartialLeadOnlineSignUpRequest($leadId, $email);

            $repository = $this->container()->get('lead_repository');
            $emailAvailabilityService = $this->container()->get('lead_email_availability_service');
            $handler = new PartialLeadOnlineSignUpHandler($repository, $emailAvailabilityService);
            $handler->handleThis($request);

            $responseArray = [
                'leadId' => $leadId,
                'email' => $email,
                '_links' => [
                    [
                        "rel" => "self",
                        "href" => url("/" . env('APP_VERSION') . "/sales/api/leads/{$leadId}"),
                        "type" => "GET",
                        "title" => "View"
                    ],
                    [
                        "rel" => "lead",
                        "href" => url("/" . env('APP_VERSION') . "/sales/api/leads/{$leadId}/signup"),
                        "type" => "PUT",
                        "title" => "Sign Up"
                    ],
                    [
                        "rel" => "self",
                        "href" => url("/" . env('APP_VERSION') . "/sales/api/leads/{$leadId}/signupTokens"),
                        "type" => "GET",
                        "title" => "Sign Up Tokens"
                    ]
                ]
            ];

            return $this->response(
                $responseArray,
                201,
                [
                    'Location' => url("/" . env('APP_VERSION') . "/sales/api/leads/{$leadId}")
                ]
            );
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    /**
     * @param Request $httpRequest
     * @return mixed|string
     * @throws Exception
     */
    private function getLeadId(Request $httpRequest)
    {
        if ($httpRequest->has('leadId')) {
            return $httpRequest->get('leadId');
        }
        return $this->container()->get('identity_generator')->nextIdentity('lead-');
    }

    public function getLead(string $leadId)
    {
        try {
            $repository = $this->container()->get('lead_repository');
            $partialSignUpRepository = $this->container()->get('partially_signed_up_leads_repository');
            $lead = $repository->findById($leadId);

            if ($lead instanceof Lead === false) {
                throw new LeadNotFoundException();
            }

            if ($lead->isDeleted()) {
                throw new DeletedLeadException();
            }

            $responseArray['leadId'] = $lead->id();
            $responseArray['accountId'] = (string)$lead->accountId();
            $responseArray['email'] = (string)$lead->email();

            $tokens = $partialSignUpRepository->findAllByLeadId($leadId);
            foreach ($tokens as $token) {
                $responseArray['signupTokens'][] = [
                    'leadId' => $token->leadId(),
                    'token' => $token->token(),
                    'signedUpOn' => $token->signedUpOn(),
                    'email' => $token->email()
                ];
            }

            $responseArray['_links'] = [
                [
                    "rel" => "self",
                    "href" => url("/" . env('APP_VERSION') . "/sales/api/leads/{$leadId}"),
                    "type" => "GET",
                    "title" => "View"
                ]
            ];

            if (array_key_exists('signupTokens', $responseArray)) {
                $responseArray['_links'][] = [
                    "rel" => "signupTokens",
                    "href" => url("/" . env('APP_VERSION') . "/sales/api/leads/{$leadId}/signupTokens"),
                    "type" => "GET",
                    "title" => "Sign Up Tokens"
                ];
            }

            return $this->response(
                $responseArray,
                200
            );
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    /**
     * @param string $leadId
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \ReflectionException
     */
    public function getLeadTokens(string $leadId)
    {
        try {
            $repository = $this->container()->get('lead_repository');
            $partialSignUpRepository = $this->container()->get('partially_signed_up_leads_repository');
            $lead = $repository->findById($leadId);

            if ($lead instanceof Lead === false) {
                throw new LeadNotFoundException();
            }

            if ($lead->isDeleted()) {
                throw new DeletedLeadException();
            }

            $responseArray['leadId'] = $lead->id();
            $tokens = $partialSignUpRepository->findAllByLeadId($leadId);
            foreach ($tokens as $token) {
                $responseArray['signupTokens'][] = [
                    'leadId' => $token->leadId(),
                    'token' => $token->token(),
                    'signedUpOn' => $token->signedUpOn(),
                    'email' => $token->email()
                ];
            }

            if (count($tokens) === 0) {
                $responseArray['signupTokens'][] = [
                    'leadId' => $lead->id(),
                    'token' => md5($lead->id() . $lead->email()),
                    'email' => $lead->email()
                ];
            }


            $responseArray['_links'] = [
                [
                    "rel" => "self",
                    "href" => url("/" . env('APP_VERSION') . "/sales/api/leads/{$leadId}/signupTokens"),
                    "type" => "GET",
                    "title" => "View"
                ],
                [
                    "rel" => "lead",
                    "href" => url("/" . env('APP_VERSION') . "/sales/api/leads/{$leadId}"),
                    "type" => "GET",
                    "title" => "Lead Record"
                ]
            ];

            return $this->response(
                $responseArray
            );
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    /**
     * @param string $leadId
     * @param string $token
     * @param string $verificationCode
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \ReflectionException
     */
    public function getLeadToken(string $leadId, string $token, string $verificationCode)
    {
        try {
            $repository = $this->container()->get('partially_signed_up_leads_repository');

            $partiallySignedUpLead = $repository->findByToken($token);
            if ($partiallySignedUpLead instanceof PartiallySignedUpLead) {
                if ($partiallySignedUpLead->signedUpOn() < strtotime('-1 day')) {
                    throw new ExpiredException();
                }

                if ($partiallySignedUpLead->leadId() !== $leadId) {
                    throw new InvalidTokenException();
                }

                if ($partiallySignedUpLead->code() !== $verificationCode) {
                    throw new InvalidVerificationCodeException();
                }

                $request = new VerifyLeadPartialSignUpRequest($leadId, $verificationCode);
                $handler = new PartiallySignedUpLeadVerificationHandler($this->container()->get('lead_repository'));
                $handler->handleThis($request);

                $responseArray = [
                    'token' => $token,
                    'createdOn' => $partiallySignedUpLead->signedUpOn(),
                    '_links' => [
                        [
                            "rel" => "self",
                            "href" => url("/" . env('APP_VERSION') .
                                "/sales/api/leads/{$leadId}/signupTokens/{$token}/code/{$verificationCode}"),
                            "type" => "GET",
                            "title" => "View"
                        ],
                        [
                            "rel" => "lead",
                            "href" => url("/" . env('APP_VERSION') . "/sales/api/leads/{$leadId}"),
                            "type" => "GET",
                            "title" => "Lead Record"
                        ],
                        [
                            "rel" => "signupTokens",
                            "href" => url("/" . env('APP_VERSION') .
                                "/sales/api/leads/{$leadId}/signupTokens"),
                            "type" => "GET",
                            "title" => "List"
                        ]
                    ]
                ];
                return $this->response(
                    $responseArray,
                    200
                );
            }

            throw new TokenNotFoundException();
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    /**
     * @param Request $httpRequest
     * @param string $leadId
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \ReflectionException
     */
    public function signup(Request $httpRequest, string $leadId)
    {
        try {
            $accountId = $this->getAccountId($httpRequest);
            $password = $httpRequest->has('password') ?
                $httpRequest->get('password') : '';
            $confirmPassword = $httpRequest->has('confirmPassword') ?
                $httpRequest->get('confirmPassword') : '';

            $repository = $this->container()->get('lead_repository');
            $accountIdAvailabilityService = $this->container()->get('account_id_availability_service');

            $encryptedPassword = md5($password);
            $encryptedPasswordConfirmation = md5($confirmPassword);

            $request = new FinalizeLeadOnlineSignUpRequest(
                $leadId,
                $accountId,
                $encryptedPassword,
                $encryptedPasswordConfirmation
            );
            $handler = new FinalizedLeadOnlineSignUpHandler($repository, $accountIdAvailabilityService);
            $handler->handleThis($request);

            $responseArray = [
                'leadId' => $leadId,
                'accountId' => $accountId,
                '_links' => [
                    [
                        "rel" => "self",
                        "href" => url("/" . env('APP_VERSION') . "/sales/api/leads/{$leadId}"),
                        "type" => "GET",
                        "title" => "View"
                    ]
                ]
            ];

            return $this->response(
                $responseArray,
                200
            );
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    /**
     * @param Request $httpRequest
     * @return mixed|string
     * @throws Exception
     */
    private function getAccountId(Request $httpRequest)
    {
        if ($httpRequest->has('accountId')) {
            return $httpRequest->get('accountId');
        }
        return $this->container()->get('identity_generator')->nextIdentity('account-');
    }
}
