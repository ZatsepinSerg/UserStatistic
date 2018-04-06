//TODO:: тут получим всё данные касательно этого пользователя

   function RetrieveData($DataContainer){

       $userStatistics = new UserStatistics();

       $searchString = array();
       $items = array();
       $visits = array();

       $date = strtotime('-31 days');
       $data =  date('Y-m-d h:i:s', $date);

       $userShotInfo = $userStatistics->getUserShotInfo($DataContainer->id);

       if($userShotInfo[0]->visit_date == NULL){
           $userShotInfo[0]->visit_date = "Не був онлайн";
       }else{
           $userShotInfo[0]->visit_date = date('d.m.Y H:i:s',strtotime($userShotInfo[0]->visit_date ));
       }

       $userShotInfo = array('FirstName' =>$userShotInfo[0]->FirstName,
           'UserName' =>$userShotInfo[0]->UserName,
           'Email' =>$userShotInfo[0]->Email,
           'visit_date' =>$userShotInfo[0]->visit_date,
           'id' =>$userShotInfo[0]->id);

     if($userShotInfo){
           $monthlyVisits = $userStatistics->getMonthlyVisits($DataContainer->id,$data);

             if(count($monthlyVisits)){
                 foreach ($monthlyVisits AS $visitsDate)
                 {
                     $visits[] = date('d.m.Y H:i:s',strtotime($visitsDate->visit_date));
                 }
             }

           $itemsFound = $userStatistics->getItemsFound($DataContainer->id,$data);

             if(count($itemsFound)){
                 foreach ($itemsFound AS $itemsProduct)
                 {
                     $items[] = $itemsProduct->Title;
                 }
             }

           $searchQueries = $userStatistics->getSearchQueries($DataContainer->id,$data);

             if(count($searchQueries)){
                 foreach ($searchQueries AS $string)
                 {
                     $searchString[] = $string->search_string;
                 }
             }
       }

       $values = array($userShotInfo,$visits,$items,$searchString);

       return $values;
    }




 public function createTable($DataContainer){
        $DataContainer->id = $DataContainer->userIdSend;

        $values = $this->RetrieveData($DataContainer);

        if(!empty($values[1])){
            $visitList = '';
            foreach ($values[1] AS $list){
                $visitList .=$list."<br>";
            }
        }else{
            $visitList="Результат вiдсутнiй";
        }

        if(!empty($values[2])){
            $searchQuery = '';
            foreach ($values[2] AS $list){
                $searchQuery .=$list."<br>";
            }
        }else{
            $searchQuery="Результат вiдсутнiй";
        }

        if(!empty($values[3])){
            $searchResult = '';
            foreach ($values[3] AS $list){
                $searchResult .=$list."<br>";
            }
        }else{
            $searchResult="Результат вiдсутнiй";
        }

    
        $res ='
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>';
        $res .='
        <table style=" border-collapse: collapse;">
            <tr>
            <th colspan="1"  style=" padding: 3px; border: 1px solid black; ">Логiн</th>
            <th colspan="1"  style=" padding: 3px; border: 1px solid black; ">Iм`я</th>
            <th colspan="1"  style=" padding: 3px; border: 1px solid black; ">Email</th>
            <th colspan="1"  style=" padding: 3px; border: 1px solid black; ">Останнiй раз заходив</th>
            </tr>
            <tr>
             <td colspan="1" style=" padding: 3px; border: 1px solid black; "><center>'.$values[0]['FirstName'].'</center></td><td colspan="1" style=" padding: 3px; border: 1px solid black; "><center>'.$values[0]['UserName'].'</center></td><td colspan="1" style=" padding: 3px; border: 1px solid black; "><center>'.$values[0] ['Email'].'</center></td><td colspan="1" style=" padding: 3px; border: 1px solid black; "><center>'.$values[0] ['visit_date'].'</center></td>
            </tr>
            <tr>
             <td colspan="1" style=" padding: 3px; border: 1px solid black; ">Заходив</td><td colspan="3" style=" padding: 3px; border: 1px solid black; ">'.$visitList.'</td>
            </tr>
            <tr>
             <td colspan="1" style=" padding: 3px; border: 1px solid black; ">Що шукав</td><td  colspan="3" style=" padding: 3px; border: 1px solid black; ">'.$searchQuery.'</td>
            </tr>
            <tr>
             <td colspan="1" style=" padding: 3px; border: 1px solid black; ">Що знайшов</td><td colspan="3" style=" padding: 3px; border: 1px solid black; ">'.$searchResult.'</td>
            </tr>
        
        </table>
        </body>
</html>
        ';


      return $res;

    }
