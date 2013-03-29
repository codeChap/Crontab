<?php

/**
 * PHP library to manage local cronjobs.
 *
 * @author     Derrick Egersdorfer
 * @license    MIT License
 * @copyright  2011 - 2013 Derrick Egersdorfer
 */

namespace Crontab;

class Crontab
{
    /**
     * Variables and there defaults
     */
    private $minute = "00";
    private $hour = "23";
    private $dayOfMonth = "*";
    private $month = "*";
    private $dayOfWeek = "*";
    private $tempFile = "jobs.txt";

    public function __construct(array $config = array())
    {
        // Get all variables of this class
        $params = array_keys( get_class_vars( get_called_class() ) );

        // Loop and set config values
        foreach( $params as $key ){
            if(array_key_exists($key, $config)){
                if( ! call_user_func(array($this, 'set'.ucFirst(strtolower($key))), $config[$key])){
                    throw new \Exception("Could not set $key to " . $config[$key] . ": " . implode($this->error) );
                }
            }
        }

        // Done
        return true;
    }

    public function setMinute($data)
    {
        $this->minute = $data;
    }

    public function setHour($data)
    {
        $this->hour = $data;
    }

    public function setDayOfMonth($data)
    {
        $this->dayOfMonth = $data;
    }

    public function setMonth($data)
    {
        $this->month = $data;
    }

    public function setDayOfWeek($data)
    {
        $this->dayOfWeek = $data;
    }

    public function setTempFile($data)
    {
        $this->tempFile = $data;
    }

    public function minuteFromNow($min = 1)
    {
        $time = strtotime("+$min minute");
        $this->setMinute(date('i', $time));
        $this->setHour(date('H', $time));
        $this->setDayOfMonth(date('j', $time));
        $this->setMonth(date('n', $time));
        $this->setDayOfWeek(date('w', $time));
        $this->setCommand("date > /dev/null");
    }

    /**
     * Ads a cronjob command to the system
     * @param array
     */
    public function append($command)
    {
        // Convert to array
        $command = is_string($command) ? array($command) : $command;

        // Build cron command lines
        foreach($command as $k => $v){
            $cmds[] = $this->buildCommandLine($v);
        }

        // Get currently set jobs and append new jobs
        $jobs = $this->buildExistingJobsArray($cmds);

        // Update Jobs
        $this->update = $jobs;
    }

    /**
     * Removes a cronjob command from the system
     * @param command to remove
     */
    public function remove($command)
    {
        // Convert to array
        $command = is_string($command) ? array($command) : $command;

        // Build cron command lines
        foreach($command as $k => $v){
            $cmds[] = $this->buildCommandLine($v);
        }

        // Get existing jobs
        $jobs = array_flip($this->buildExistingJobsArray());

        // Loop to find and remove jobs
        foreach($cmds as $k => $v){
            if(array_key_exists($v, $jobs)){
                unset($jobs[$v]);
            }
        }

        // Flip back and ready to rewrite the new cronjob file
        $jobs = array_flip($jobs);

        // Update Jobs
        $this->update = $jobs;
    }

    /**
     * Removes all cronjobs
     */
    public function clear()
    {
        exec("crontab -r");
    }

    /**
     * Update the systems contab file with
     * @param jobs array
     * @return boolean
     */
    public function execute()
    {
        // Write the new cronjob data
        $this->writeTmpCronFile($this->update);

        // Set cron to temp cronfile
        exec("crontab jobs.txt 2>&1 >> log.txt", $output, $status);

        // Done
        return true;
    }

    /**
     * Builds a single command line entry for the contab temp file
     * @param string
     * @return string
     */
    private function buildCommandLine($command)
    {
        // Build time
        $time = implode(" ", array(
                $this->minute,
                $this->hour,
                $this->dayOfMonth,
                $this->month,
                $this->dayOfWeek
            )
        );

        // Build single command line row
        return $time . " " . trim($command);
    }

    /**
     * Builds an array of active cronjob command on the system
     * @param string of a new command
     * @return array
     */
    private function buildExistingJobsArray($append = false)
    {
        // Existing jobs
        exec("crontab -l ", $output, $status);

        // Clean up contab array just incase
        if(count($output) > 0){
            foreach($output as $k => $v){
                $array[] = trim($v);
            }
        }

        // Append new jobs
        if($append){

            // Convert to array if need be
            $append = is_string($append) ? array($append) : $append;

            // Append to exisiting array or new array
            foreach($append as $k => $v){
                $array[] = trim($v);
            }
        }

        // Done
        return isset($array) ? array_unique($array) : array();
    }

    /**
     * Writes a temp file for the crontab program to read
     * @param $content = new command
     * @return boolean
     */
    private function writeTmpCronFile($content)
    {
        // Convert to array
        $content = is_string($content) ? array($content) : $content;

        // Set string
        $string = null;

        // Cleanup content if array
        if( ! empty($content)){
            foreach($content as $k => $v){
                $string[] = trim($v);
            }
            $string = implode(PHP_EOL, $string);
        }

        // Set temp file
        $filename = $this->tempFile;

        // Open the temp file
        if ( ! $handle = fopen($filename, 'w')) {
            throw new \Exception("Cannot open $filename");
            exit;
        }

        // And write in content
        if (fwrite($handle, $string) === FALSE) {
            throw new \Exception("Cannot write to $filename");
            exit;
        }

        // Done
        fclose($handle);
        return true;
    }

    /**
     * Clean up
     */
    public function __destruct()
    {
        if(file_exists($this->tempFile)){
            //exec("rm jobs.txt");
        }
    }
}