<?php

/**
 * Created by PhpStorm.
 * User: secret
 * Date: 28.03.18
 * Time: 14:59
 */

include_once('libphp/base.obj.inc.php');

class UserStatistics extends NeadsBaseUtil
{
    public $data;
    
    /****************************************************************************************************************
                This block executes the requests to record statistical information on the actions of users on the site
     ***************************************************************************************************************/

    /**
     * @param $user_id
     * @param $visit_status
     * @return int
     */
    public function visitUpdate($user_id, $visit_status)
    {
        if ($user_id) {
            $Query = "UPDATE " . TableUsers . " SET `visit_status`='{$visit_status}' ,`visit_date` = CURRENT_TIMESTAMP
                    WHERE id='{$user_id}'";

            return parent::Update($Query);
        }
    }

    /**
     * @param $user_id
     * @return int
     */
    public function visitStatistic($user_id)
    {
        if ($user_id) {

            $query ="INSERT INTO `main__statistic_user` ( `user_id`,`visit`, `visit_date`) VALUES ('{$user_id}','1', CURRENT_TIMESTAMP);";

            return parent::Query($query);
        }
    }

    /**
     * @param $failLogin
     * @param $failPass
     */
    public function failedAuthUser($failLogin, $failPass){

        $query ="INSERT INTO `main__statistic_user` ( `fail_login`, `fail_password`) VALUES ('{$failLogin}', '{$failPass}');";

        parent::Query($query);
    }

    /**
     * @param $userID
     * @param $searchString
     */
    public function userSearchQuery($userID, $searchString,$productID){

        $query ="INSERT INTO `main__statistic_user` ( `user_id`, `search_string`,`product_id`) 
                              VALUES ('{$userID}', '{$searchString}', '{$productID}');";

        parent::Query($query);
    }


    /**
     * @param $userID
     * @param $date
     */
    public function trackingBasket($userID){

        if ($userID) {
            $Query = "UPDATE " . TableUsers . " SET `visit_basket` = CURRENT_TIMESTAMP
                    WHERE id='{$userID}'";

            return parent::Update($Query);
        }
    }
    
    /**
     * @param $userID
     * @param $date
     */
    
    public function trackingActivity($userID){

        if ($userID) {
            $Query = "UPDATE " . TableUsers . " SET `visit_date` = CURRENT_TIMESTAMP
                    WHERE id='{$userID}'";

            return parent::Update($Query);
        }
    }
    /****************************************************************************************************************
                     This block displays information in the main table on the statistics page
     ***************************************************************************************************************/

    /**
     *
     * @param $data
     */
    
    public function visitToday($data){
        
        $query ="SELECT `id`,`UserName`,`FirstName`,`visit_date` FROM  " . TableUsers . " WHERE `visit_date` > '{$data}' ORDER BY `visit_date` DESC ";

        parent::Query($query);
        return $this->Items;
    }

    /**
     *
     */
    public function usersOnline(){
        $date = strtotime('-15 minutes');
        $data =  date('Y-m-d H:i:s', $date);


        $query ="SELECT `id`,`UserName`,`FirstName`,`visit_date` FROM  " . TableUsers . " WHERE 
                                   `visit_date` > '{$data}'   ORDER BY `visit_date` DESC ";

        parent::Query($query);
        return $this->Items;
    }

    /**
     *
     */
    public function usersInBucket(){

        $date = strtotime('-5 minutes');
        $data =  date('Y-m-d H:i:s', $date);

        $query ="SELECT `id`,`UserName`,`FirstName`,`visit_date` FROM  " . TableUsers . " 
                       WHERE  `visit_basket` > '{$data}'  
                              ORDER BY `visit_basket` DESC ";

        parent::Query($query);
        return $this->Items;
    }

    /**
     *
     */
    public function noVisitsUsers(){

        $query =" SELECT " . TableUsers . ".`id`,`UserName`,`FirstName` FROM " . TableUsers . " LEFT JOIN `main__order` 
            ON " . TableUsers . ".`id`=`main__order`.`id_user` WHERE (`main__order`.`id_user` IS NULL AND `visit_date` IS NULL )";
        
        parent::Query($query);
        return $this->Items;
    }

    /**
     *
     */
    public function onePurchase(){

        $query ="SELECT count(`id_user`) num,`id_user`,`UserName`,`FirstName` FROM `main__order`  
                      LEFT JOIN `main__users` ON `main__order`.`id_user` = `main__users`.`id` 
                           GROUP BY `id_user` having count(`id_user`) = 1  
                                AND  `UserName` IS NOT NULL  AND  `FirstName` IS NOT NULL";

        parent::Query($query);
        return $this->Items;
    }

    /**
     *
     */
    public function failLogin(){
        $query ="SELECT `fail_login`,`fail_password` FROM `main__statistic_user` WHERE `fail_login` != '' AND `fail_password` != '' ";

        parent::Query($query);
        return $this->Items;
    }

    /**
     *
     */
    public function unorderedOrder(){
        $query ="SELECT `id`,`UserName`,`FirstName` FROM `main__basket` LEFT JOIN `main__users` ON `main__basket`.`id_user` = `main__users`.`id`";

        parent::Query($query);
        return $this->Items;
      }


    /****************************************************************************************************************
                        This block contains requests for information on a particular user
     ***************************************************************************************************************/
    public function getUserShotInfo($userID){
        $query = "SELECT `FirstName`,`UserName`,`Email`,`visit_date`,`id` FROM `main__users` WHERE `id`='{$userID}' ";
        
        parent::Query($query);
        return $this->Items;
      }

    public function getMonthlyVisits($userID,$data){
        $query = "SELECT `visit_date` FROM `main__statistic_user` WHERE `user_id` = '{$userID}' AND `visit` = 1 AND `visit_date` > '{$data}' ORDER BY `fail_login` DESC";

        parent::Query($query);
        return $this->Items;
    }

    public function getSearchQueries($userID,$data){
        $query = "SELECT DISTINCT `search_string` FROM `main__statistic_user` WHERE `user_id` = '{$userID}' 
                                      AND `created_at` > '{$data}' AND `search_string` != '' ORDER BY `fail_login` DESC";

        parent::Query($query);
        return $this->Items;
    }
    
    
     public function getItemsFound($userID,$data){
        $query = "SELECT DISTINCT `product_id`,`Title` FROM `main__statistic_user` 
                           LEFT JOIN `main__catalog` ON `main__statistic_user`.`product_id` = `main__catalog`.`id` 
                                 WHERE `user_id` = '{$userID}' AND `created_at` > '{$data}' AND `search_string` != '' 
                                          AND `product_id` != 0 ORDER BY `fail_login` DESC ";

            parent::Query($query);
            return $this->Items;
        }
    

}