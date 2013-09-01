<?php

    class CombineCrons {
        
		private $cronAdd;
		
		public function ReturnCron($_cronBegin, $_cronAdd) {
			$this->cronAdd = $_cronAdd;
			$this->cronAdd = explode(" ", $this->cronAdd);
			return $this->SplitCrons($_cronBegin);
		}
		
        //Set the $this->increment and explode the initial cron value
        private $increment = 0;
    
        private function AddCrons($cronStart, $timeType) {
			
            if ($this->cronAdd[$timeType] != "*" && $cronStart != "*") {
                
                $timeAdded;
                $timesDivisible = 0;
                $divisor = 0;
                //Minute
                if ($timeType == 0) {
                    $divisor = 60;
                    $timeAdded = (intval($cronStart) + intval($this->cronAdd[$timeType])) % $divisor;
                    if (((intval($cronStart) + intval($this->cronAdd[$timeType]) - $timeAdded) / $divisor) >= 1) 
                        $this->increment = 1;
                } //Hour
                else if ($timeType == 1) {
                    $divisor = 24;
                    $timeAdded = (intval($cronStart) + intval($this->cronAdd[$timeType])) % $divisor;
                    if (((intval($cronStart) + intval($this->cronAdd[$timeType]) - $timeAdded) / $divisor) >= 1) 
                        $this->increment = 1;
                } //DotM
                else if ($timeType == 2) {
                    $divisor = 32;
                    $timeAdded = (intval($cronStart) + intval($this->cronAdd[$timeType])) % $divisor;
                    if (((intval($cronStart) + intval($this->cronAdd[$timeType]) - $timeAdded) / $divisor) >= 1) 
                        $this->increment = 1;
                    if ($timeAdded < $cronStart)
                        $timeAdded++; //Can't be zero
                } //Month
                else if ($timeType == 3) {
                    $divisor = 13;
                    $timeAdded = (intval($cronStart) + intval($this->cronAdd[$timeType])) % $divisor;
                    if (((intval($cronStart) + intval($this->cronAdd[$timeType]) - $timeAdded) / $divisor) >= 1) 
                        $this->increment = 1;
                    if ($timeAdded < $cronStart)
                        $timeAdded++; //Can't be zero
                } //DotW
                else if ($timeType == 4) {
                    $divisor = 7;
                    $timeAdded = (intval($cronStart) + intval($this->cronAdd[$timeType])) % $divisor;
                    if (((intval($cronStart) + intval($this->cronAdd[$timeType]) - $timeAdded) / $divisor) >= 1) 
                        $this->increment = 1;
                } 
                else {
                    $timeAdded = intval($cronStart) + intval($this->cronAdd[$timeType]);
                }
                
                return $timeAdded;
				
            } else {
                return $cronStart;
            };
        }
    
        private function SplitCrons($cronStart, $timeType=0) {
	
            $cronStart_split = "";
    
            if (strpos($cronStart," ") !== false) {
                
                $cronStart_split = explode(" ", $cronStart);
                $cronStart = "";
                
                for ($count = 0; $count < count($cronStart_split); $count++) {
                                        
                    $cronStart = $cronStart.$this->SplitCrons($cronStart_split[$count], $count);
                    //echo "<div>$count: $cronStart</div>";
					
                    if ($count != (count($cronStart_split) - 1))
                        $cronStart = $cronStart." ";
                    if ($this->increment > 0) {
                        //Minute
                        if ($count == 0) {
                            if ($this->cronAdd[$count + 1] != "*") 
                                $this->cronAdd[$count + 1] = intval($this->cronAdd[$count + 1]) + intval($this->increment);
                            else
                                $this->cronAdd[$count + 1] = intval($this->increment);
                        } //Hour
                        else if ($count == 1) {
                            if ($this->cronAdd[$count + 1] != "*" && $this->cronAdd[count($this->cronAdd) - 1] != "*") {
                                $this->cronAdd[$count + 1] = intval($this->cronAdd[$count + 1]) + intval($this->increment);
                                $this->cronAdd[count($this->cronAdd) - 1] = intval($this->cronAdd[count($this->cronAdd) - 1]) + intval($this->increment);
                            } else if ($this->cronAdd[$count + 1] != "*") {
                                $this->cronAdd[$count + 1] = intval($this->cronAdd[$count + 1]) + intval($this->increment);
                                $this->cronAdd[count($this->cronAdd) - 1] = intval($this->increment);
                            } else if ($this->cronAdd[count($this->cronAdd) - 1] != "*") {
                                $this->cronAdd[$count + 1] = intval($this->increment);
                                $this->cronAdd[count($this->cronAdd) - 1] = intval($this->cronAdd[count($this->cronAdd) - 1]) + intval($this->increment);
                            } else {
                                $this->cronAdd[$count + 1] = intval($this->increment);
                                $this->cronAdd[count($this->cronAdd) - 1] = intval($this->increment);
                            }
                        } //DotM
                        else if ($count == 2) {
                            if ($this->cronAdd[$count + 1] != "*") 
                                $this->cronAdd[$count + 1] = intval($this->cronAdd[$count + 1]) + intval($this->increment);
                            else
                                $this->cronAdd[$count + 1] = intval($this->increment);
                        } //Month
                        else if ($count == 3) {
                            //No year to add
                        } //DotW
                        else if ($count == 4)
                            if ($this->cronAdd[$count - 1] != "*") 
                                $this->cronAdd[$count - 1] = intval($this->cronAdd[$count - 1]) + intval($this->increment);
                            else
                                $this->cronAdd[$count - 1] = intval($this->increment);
                    };
                    $this->increment = 0;
                    
                };
    
            } else if (strpos($cronStart,",") !== false) {
                
                $cronStart = explode(",", $cronStart);
                for ($count = 0; $count < count($cronStart); $count++) {
                    $cronStart[$count] = $this->SplitCrons($cronStart[$count], $timeType);
                }
                //$cronStart.sort(function (a, b) {return a - b;});
                $cronStart = implode(",", $cronStart);
    
            } else if (strpos($cronStart,"-") !== false) {
                
                $cronStart = explode("-", $cronStart);
                for ($count = 0; $count < count($cronStart); $count++) {
                    $cronStart[$count] = $this->SplitCrons($cronStart[$count], $timeType);
                }
                $cronStart = implode("-", $cronStart);
                
            } else {
            	
                $cronStart = $this->AddCrons($cronStart, $timeType);
                
            };
            
            return $cronStart;
        }
        
    };

	$CombineCrons = new CombineCrons();
	/*$startCron = "1,2 1-2 0 0 0";
	$addCron = "1 23 0 0 0";
	echo "<div>$startCron</div>";
	echo "<div>$addCron</div>";*/
	//echo "<div>".$CombineCrons->ReturnCron($startCron, $addCron)."</div>";

?>
