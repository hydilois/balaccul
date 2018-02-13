<?php 
namespace AccountBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AccountBundle\Entity\Saving;

/**
* Account Controller for Webservices using REST
*/
class AccountControllerApi extends FOSRestController{

    /**
     * get an account instance
     * @Rest\Get("/account_api/{id}/{accountCategory}", name="api_account_saving")
     */
    public function getAccountAction($id, $accountCategory){
        $accountService = $this->get('app.account_service');
        switch ($accountCategory) {
            case 1:
                $savingAccount  = $accountService->getSavingAccount($id);
                if ($savingAccount->getMoralMember()) {
                    $representants = $accountService->getAccountRepresentant($savingAccount->getMoralMember()->getId());
                    return [
                        "message" => "Entite Saving", 
                        "status" => "success", 
                        "data" => $savingAccount,
                        "representants" => $representants
                    ];    
                }else{
                    $beneficiaries = $accountService->getAccountBeneficiary($savingAccount->getPhysicalMember()->getId());
                    return [
                        "message" => "Entite Saving", 
                        "status" => "success", 
                        "data" => $savingAccount,
                        "beneficiaries" => $beneficiaries
                    ];
                }
                break;
            case 2:
                $shareAccount  = $accountService->getShareAccount($id);
                if ($shareAccount->getMoralMember()) {
                    $representants = $accountService->getAccountRepresentant($shareAccount->getMoralMember()->getId());
                    return [
                        "message" => "Entity Deposit", 
                        "status" => "success", 
                        "data" => $shareAccount,
                        "representants" => $representants
                    ];    
                }else{
                    $beneficiaries = $accountService->getAccountBeneficiary($shareAccount->getPhysicalMember()->getId());
                    return [
                        "message" => "Entity Deposit", 
                        "status" => "success", 
                        "data" => $shareAccount,
                        "beneficiaries" => $beneficiaries
                    ];
                }
                break;
            case 3:
                $depositAccount  = $accountService->getDepositAccount($id);
                if ($depositAccount->getMoralMember()) {
                    $representants = $accountService->getAccountRepresentant($depositAccount->getMoralMember()->getId());
                    return [
                        "message" => "Entity Deposit", 
                        "status" => "success", 
                        "data" => $depositAccount,
                        "representants" => $representants
                    ];    
                }else{
                    $beneficiaries = $accountService->getAccountBeneficiary($depositAccount->getPhysicalMember()->getId());
                    return [
                        "message" => "Entity Deposit", 
                        "status" => "success", 
                        "data" => $depositAccount,
                        "beneficiaries" => $beneficiaries
                    ];
                }
              break;
            default:
                # code...
                break;
        }
    }


    /**
     * get an account instance
     * @Rest\Post("/account_list", name="api_account_list")
     */
    public function getAccountListAction(Request $request){

        $requestParsed  = json_decode(json_encode($request->request->get("data")));

        try{
            $idCategory    = $requestParsed->idCategory;
        
            $accountService = $this->get('app.account_service');

            switch ($idCategory) {
                case 1:
                    $savingAccounts  = $accountService->getSavingAccountList();
                    return [
                        "message" => "Entite Saving", 
                        "status" => "success", 
                        "data" => $savingAccounts,
                    ];
                    break;
                case 2:
                    $shareAccounts  = $accountService->getShareAccountList();
                    return [
                        "message" => "Entite Share", 
                        "status" => "success", 
                        "data" => $shareAccounts,
                    ];
                    break;
                case 3:
                    $depositAccounts  = $accountService->getDepositAccountList();
                    return [
                        "message" => "Entite Share", 
                        "status" => "success", 
                        "data" => $depositAccounts,
                    ];
                  break;
                default:
                    break;
            }
        }catch(Exception $ex){
                return json_encode([
                    "message" => "Error while pulling informations", 
                    "params" => $request, 
                    "status" => "failed", 
                    "data" => json_decode(json_encode([])),
                ]);
            }
    }



    /**
     * get an loanHistor instance
     * @Rest\Get("/loan_api/{id}", name="api_loan")
     */
    public function getLoanAction($id){
        $em = $this->getDoctrine()->getManager();
        $accountService = $this->get('app.account_service');
        
        $loan  = $accountService->getLoan($id);

        $loanHistory  = $accountService->getLoanHistory($id);

        if ($loanHistory) {
            $interest = ($loanHistory->getRemainAmount() * $loan->getRate())/100;
            $dailyInterestPayment = $interest/30;
            
            $date = strtotime($loanHistory->getDateOperation()->format('Y-m-d'));
            $dateNow = time();

            // $days = $dateNow - $date;
            $days = floor(($dateNow - $date)/(60*60*24));
            $interestToPay = $dailyInterestPayment * $days;
        }else{
            $interest = ($loan->getLoanAmount() * $loan->getRate())/100;
            $dailyInterestPayment = $interest/30;
            
            $date = strtotime($loan->getDateLoan()->format('Y-m-d'));
            $dateNow = time();

            $interestToPay = $dailyInterestPayment * floor(($dateNow - $date)/(60*60*24));
        }
                    $beneficiaries = $accountService->getAccountBeneficiary($loan->getPhysicalMember()->getId());
                    return [
                        "message" => "Entite Loan", 
                        "status" => "success", 
                        "data" => $loan,
                        "beneficiaries" => $beneficiaries,
                        "loanhistory" => $loanHistory,
                        "interestToPay" => round($interestToPay)
                    ];
    }


    /**
     * get an account instance
     * @Rest\Get("/client_api/{id}", name="api_client")
     */
    public function getClientAction($id){
        $em = $this->getDoctrine()->getManager();

        $accountService = $this->get('app.account_service');
        
        $client  = $accountService->getClient($id);
            return [
                "message" => "Entite Client", 
                "status" => "success", 
                "data" => $client,
            ];   
    }


    /**
     * get an account instance
     * @Rest\Get("/internalaccount_api/{id}", name="api_internalaccount")
     */
    public function getInternalAccountAction($id){
        $em = $this->getDoctrine()->getManager();

        $accountService = $this->get('app.account_service');
        
        $account  = $accountService->getInternalAccount($id);
            return [
                "message" => "Entite Internal Account", 
                "status" => "success",
                "data" => $account,
            ];   
    }
}