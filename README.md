    /*
     * Title:
     * 1. CronAdd
     *
     * Author:
     * 1. Connor Schlesiger
     * 
     * Questions: 
     * 1. Can CRON understand time ranges which cross over
     *    the limit? (ie. minutes: 58-4)
     *
     * Bugs/Issues:
     * 1. Does not sort properly (ie. 2 is higher than 10)
     *      ^- Does this matter with cron?
     * 2. No check for out of Month ranges (ie. Feb 31st)
     * 3. Going over a day range in hours causes the day to 
     *    increment, however this is not correct for those
     *    hours which remain under the day range (>24).
     */
