
    var CombineCrons = function (cronBegin, cronAdd) {
        
        //Set the increment and explode the initial cron value
        var increment = 0;
        cronAdd = cronAdd.split(" ");
    
        var AddCrons = function (cronStart, timeType) {
            
            if (cronAdd[timeType] != "*" && cronStart != "*") {
                
                var timeAdded;
                var timesDivisible = 0;
                var divisor = 0;
                //Minute
                if (timeType == 0) {
                    divisor = 60;
                    timeAdded = (+cronStart + +cronAdd[timeType]) % divisor;
                    if (((+cronStart + +cronAdd[timeType] - timeAdded) / divisor) >= 1) 
                        increment = 1;
                } //Hour
                else if (timeType == 1) {
                    divisor = 24;
                    timeAdded = (+cronStart + +cronAdd[timeType]) % divisor;
                    if (((+cronStart + +cronAdd[timeType] - timeAdded) / divisor) >= 1) 
                        increment = 1;
                } //DotM
                else if (timeType == 2) {
                    divisor = 32;
                    timeAdded = (+cronStart + +cronAdd[timeType]) % divisor;
                    if (((+cronStart + +cronAdd[timeType] - timeAdded) / divisor) >= 1) 
                        increment = 1;
                    if (timeAdded < cronStart)
                        timeAdded++; //Can't be zero
                } //Month
                else if (timeType == 3) {
                    divisor = 13;
                    timeAdded = (+cronStart + +cronAdd[timeType]) % divisor;
                    if (((+cronStart + +cronAdd[timeType] - timeAdded) / divisor) >= 1) 
                        increment = 1;
                    if (timeAdded < cronStart)
                        timeAdded++; //Can't be zero
                } //DotW
                else if (timeType == 4) {
                    divisor = 7;
                    timeAdded = (+cronStart + +cronAdd[timeType]) % divisor;
                    if (((+cronStart + +cronAdd[timeType] - timeAdded) / divisor) >= 1) 
                        increment = 1;
                } 
                else {
                    timeAdded = +cronStart + +cronAdd[timeType];
                }
                
                return timeAdded;
            } else {
                return cronStart;
            };
        };
    
        var SplitCrons = function (cronStart, timeType) {
    
            var cronStart_split = "";
    
            if (cronStart.indexOf(" ") > -1) {
                
                cronStart_split = cronStart.split(" ");
                cronStart = "";
                
                for (var count = 0; count < cronStart_split.length; count++) {
                    
                    cronStart += SplitCrons(cronStart_split[count], count);
                    if (count != (cronStart_split.length - 1))
                        cronStart += " ";
                    if (increment > 0) {
                        //Minute
                        if (count == 0) {
                            if (cronAdd[count + 1] != "*") 
                                cronAdd[count + 1] = +cronAdd[count + 1] + +increment;
                            else
                                cronAdd[count + 1] = +increment;
                        } //Hour
                        else if (count == 1) {
                            if (cronAdd[count + 1] != "*" && cronAdd[cronAdd.length - 1] != "*") {
                                cronAdd[count + 1] = +cronAdd[count + 1] + +increment;
                                cronAdd[cronAdd.length - 1] = +cronAdd[cronAdd.length - 1] + +increment;
                            } else if (cronAdd[count + 1] != "*") {
                                cronAdd[count + 1] = +cronAdd[count + 1] + +increment;
                                cronAdd[cronAdd.length - 1] = +increment;
                            } else if (cronAdd[cronAdd.length - 1] != "*") {
                                cronAdd[count + 1] = +increment;
                                cronAdd[cronAdd.length - 1] = +cronAdd[cronAdd.length - 1] + +increment;
                            } else {
                                cronAdd[count + 1] = +increment;
                                cronAdd[cronAdd.length - 1] = +increment;
                            }
                        } //DotM
                        else if (count == 2) {
                            if (cronAdd[count + 1] != "*") 
                                cronAdd[count + 1] = +cronAdd[count + 1] + +increment;
                            else
                                cronAdd[count + 1] = +increment;
                        } //Month
                        else if (count == 3) {
                            //No year to add
                        } //DotW
                        else if (count == 4)
                            if (cronAdd[count - 1] != "*") 
                                cronAdd[count - 1] = +cronAdd[count - 1] + +increment;
                            else
                                cronAdd[count - 1] = +increment;
                    };
                    increment = 0;
                    
                };
    
            } else if (cronStart.indexOf(",") > -1) {
                
                cronStart = cronStart.split(",");
                for (var count = 0; count < cronStart.length; count++) {
                    cronStart[count] = SplitCrons(cronStart[count], timeType);
                }
                cronStart.sort(function (a, b) {return a - b;});
                cronStart = cronStart.join(",");
    
            } else if (cronStart.indexOf("-") > -1) {
                
                cronStart = cronStart.split("-");
                for (var count = 0; count < cronStart.length; count++) {
                    cronStart[count] = SplitCrons(cronStart[count], timeType);
                }
                cronStart = cronStart.join("-");
                
            } else {
                
                cronStart = AddCrons(cronStart, timeType);
                
            };
            return cronStart;
            
        };
        
        return SplitCrons(cronBegin);
        
    };

