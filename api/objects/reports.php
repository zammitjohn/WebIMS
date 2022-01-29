<?php
// include object files
include_once 'base.php';

class reports extends base{
 
    // database table name
    protected $table_name = "reports";
 
    // object properties
    public $inventoryId;
    public $ticketNo;
    public $name;
    public $description;
    public $reportNo;
    public $assigneeUserId;
    public $faultySN;
    public $replacementSN;
    public $dateRequested;
    public $dateLeaving;
    public $dateDispatched;
    public $dateReturned;
    public $AWB;
    public $AWBreturn;
    public $RMA;

    // read reports
    function read(){

        // filter by pending reports assigned to user
        if ($this->userId) {                        
            $query = "SELECT * 
                    FROM `reports` 
                    WHERE assigneeUserId = '".$this->userId."' AND ((replacementSN IS NULL) AND (dateReturned IS NULL) AND (isRepairable = '1'))
                    ORDER BY `id` DESC";   

        } else {    
            // select all query
            $query = "SELECT *, 
                    CASE WHEN replacementSN IS NOT NULL THEN '1'
                        WHEN dateReturned IS NOT NULL THEN '1'
                        ELSE '0' END AS isClosed
                    FROM " . $this->table_name . " 
                    ORDER BY `id` DESC";                       
        }
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // get single report data
    function read_single(){

        // select query
        $query = "SELECT *, 
                CASE WHEN replacementSN IS NOT NULL THEN '1'
                    WHEN dateReturned IS NOT NULL THEN '1'
                    ELSE '0' END AS isClosed
                FROM " . $this->table_name . " 
                WHERE id= '".$this->id."'";                        
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create report
    function create(){
        
        // query to insert record
        $query = "INSERT INTO  
                    ". $this->table_name ."
                SET
                    inventoryId=:inventoryId, ticketNo=:ticketNo, name=:name, description=:description, reportNo=:reportNo, assigneeUserId=:assigneeUserId, 
                    faultySN=:faultySN, replacementSN=:replacementSN, dateRequested=:dateRequested, 
                    dateLeaving=:dateLeaving, dateDispatched=:dateDispatched, 
                    dateReturned=:dateReturned, AWB=:AWB, AWBreturn=:AWBreturn, RMA=:RMA";
    
        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindValues($stmt);

        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->id = $this->conn->lastInsertId();
            $this->logging(null);
            return true;
        }

        return false;
    }
      
    // update report 
    function update(){ 
        $old_row = $this->selectRow();
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    inventoryId=:inventoryId, ticketNo=:ticketNo, name=:name, description=:description, reportNo=:reportNo, assigneeUserId=:assigneeUserId, 
                    faultySN=:faultySN, replacementSN=:replacementSN, dateRequested=:dateRequested, 
                    dateLeaving=:dateLeaving, dateDispatched=:dateDispatched, 
                    dateReturned=:dateReturned, AWB=:AWB, AWBreturn=:AWBreturn, RMA=:RMA                
                
                WHERE
                    id='".$this->id."'";
    
        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindValues($stmt);
        
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->logging($old_row);
            return true;
        }
        return false;
    }

    // toggle isRepairable bool
    function toggle_repairable(){
        $old_row = $this->selectRow();
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "

                SET isRepairable = !isRepairable          
                
                WHERE
                    id='".$this->id."'";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->logging($old_row);
            return true;
        }
        return false;
    }    

    private function bindValues($stmt){
        if ($this->inventoryId == ""){
            $stmt->bindValue(':inventoryId', $this->inventoryId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':inventoryId', $this->inventoryId);
        }
        if ($this->ticketNo == ""){
            $stmt->bindValue(':ticketNo', $this->ticketNo, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':ticketNo', $this->ticketNo);
        }
        if ($this->name == ""){
            $stmt->bindValue(':name', $this->name, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':name', $this->name);
        }
        if ($this->description == ""){
            $stmt->bindValue(':description', $this->description, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':description', $this->description);
        }
        if ($this->reportNo == ""){
            $stmt->bindValue(':reportNo', $this->reportNo, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':reportNo', $this->reportNo);
        }                   
        if ($this->assigneeUserId == ""){
            $stmt->bindValue(':assigneeUserId', $this->assigneeUserId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':assigneeUserId', $this->assigneeUserId);
        }        
        if ($this->faultySN == ""){
            $stmt->bindValue(':faultySN', $this->faultySN, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':faultySN', $this->faultySN);
        }        
        if ($this->replacementSN == ""){
            $stmt->bindValue(':replacementSN', $this->replacementSN, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':replacementSN', $this->replacementSN);
        }        
        if ($this->dateRequested == ""){
            $stmt->bindValue(':dateRequested', $this->dateRequested, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':dateRequested', $this->dateRequested);
        }        
        if ($this->dateLeaving == ""){
            $stmt->bindValue(':dateLeaving', $this->dateLeaving, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':dateLeaving', $this->dateLeaving);
        }        
        if ($this->dateDispatched == ""){
            $stmt->bindValue(':dateDispatched', $this->dateDispatched, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':dateDispatched', $this->dateDispatched);
        }      
        if ($this->dateReturned == ""){
            $stmt->bindValue(':dateReturned', $this->dateReturned, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':dateReturned', $this->dateReturned);
        }
        if ($this->AWB == ""){
            $stmt->bindValue(':AWB', $this->AWB, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':AWB', $this->AWB);
        }
        if ($this->AWBreturn == ""){
            $stmt->bindValue(':AWBreturn', $this->AWBreturn, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':AWBreturn', $this->AWBreturn);
        }
        if ($this->RMA == ""){
            $stmt->bindValue(':RMA', $this->RMA, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':RMA', $this->RMA);
        }
        return $stmt;
    }

}